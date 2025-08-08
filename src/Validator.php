<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Fluents\Error;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class Validator
{
    /**
     * To hold & manage all of the input data that we want to validate.
     *
     * @var \AdityaZanjad\Validator\Fluents\Input $input
     */
    protected Input $input;

    /**
     * To hold the validation rules that we want to apply against the given input data.
     *
     * @var array<string, string|array<int, string|\AdityaZanjad\Validator\Base\AbstractRule|callable(int|string $field, mixed $value): bool>>
     */
    protected array $rules;

    /**
     * To hold the custom validation error messages for the specified validation rules.
     *
     * @var array<string, string> $messages
     */
    protected array $messages;

    /**
     * To hold & manage the validation errors.
     *
     * @var \AdityaZanjad\Validator\Fluents\Error $errors
     */
    protected Error $errors;

    /**
     * Useful in deciding whether or not to stop the validator on first failure.
     *
     * @var bool $shouldStopOnFailure
     */
    protected bool $shouldStopOnFailure;

    /**
     * To check whether the validation has been already performed or not.
     *
     * @var bool $alreadyValidated
     */
    protected bool $alreadyValidated;

    /**
     * Inject all the necessary parameters to perform the validation.
     *
     * @param   array<int|string, mixed>
     * @param   array<string, string|array<int, string|\AdityaZanjad\Validator\Base\AbstractRule|callable(int|string $field, mixed $value): bool>>
     * @param   array<string, string>
     */
    public function __construct(array $input, array $rules, array $messages = [])
    {
        // Move the internal pointer at the start of array.
        reset($rules);

        // Initialize/transform the supplied constructor arguments before utilizing them.
        $this->input    =   new Input($input);
        $this->rules    =   $rules;
        $this->messages =   $messages;

        // Set other necessary data to their default options.
        $this->errors               =   new Error();
        $this->shouldStopOnFailure  =   false;
        $this->alreadyValidated     =   false;
    }

    /**
     * Stop the validation process immediately on the first validation failure.
     *
     * @param bool $shouldStop
     *
     * @return \AdityaZanjad\Validator\Validator
     */
    public function stopOnFirstFailure(bool $shouldStop = true): static
    {
        $this->shouldStopOnFailure = $shouldStop;
        return $this;
    }

    /**
     * Perform the validation process.
     *
     * @throws \Exception
     *
     * @return static
     */
    public function validate(): static
    {
        // Do not allow performing the same validation more than once.
        if ($this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: The validation for this instance has been already performed once.");
        }

        $field = null;

        // Keep looping & validating each field until there are no more rules left in the array.
        while (!\is_null($field = \key($this->rules))) {
            $field  =   (string) $field;
            $rules  =   \current($this->rules);

            // If rules are provided in a string format, then first convert them into an array of rules.
            if (\is_string($rules)) {
                $rules = explode('|', $rules);
            }

            // Whether the user provides the rules in either a [STRING] or [ARRAY] formats, 
            // they should ultimately end up in the array format.
            if (!\is_array($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] must be either a [STRING] or an [ARRAY]");
            }

            // If the current field contains wildcard paths, we need to find out the actual 
            // paths corresponding to the wildcard parameters path and then add those 
            // paths to the rules array.
            if (\preg_match('/(\*|\.\*)/', $field) > 0) {
                $this->rules = \array_merge($this->rules, $this->resolveWildCardsPath($field, $rules));

                unset($this->rules[$field]);
                \next($this->rules);
                continue;
            }

            foreach ($rules as $index => $rule) {
                if (\is_string($rule)) {
                    $rule = $this->makeRuleInstanceFromString($rule, $index, $field);
                }

                if (\is_callable($rule)) {
                    $rule = new Callback($rule);
                }

                // If the input field is equal to NULL & the rule is not set to be run mandatorily.
                if (\is_null($rule)) {
                    continue;
                }

                if (!$rule instanceof AbstractRule) {
                    throw new Exception("[Developer][Exception]: The field [{$field}] contains an invalid rule at the index [{$index}]");
                }

                // If the validation succeeds, there is no need to proceed down further.
                if ($rule->setInput($this->input)->check($field, $this->input->get($field)) === true) {
                    continue;
                }

                // Make necessary transformations to the error message.
                $validationError = $this->messages[$field] ?? $rule->message();
                $validationError = \str_replace(':{field}', $field, $validationError);

                $this->errors->add($field, $validationError);

                if ($this->shouldStopOnFailure) {
                    break 2;
                }
            }

            \next($this->rules);
        }

        $this->alreadyValidated = true;
        return $this;
    }

    /**
     * Expand the given wildcard parameters path to its actual input data paths.
     *
     * @param   string              $path
     * @param   array<int, mixed>   $rules
     *
     * @return  array<int, string>  $actualPaths
     */
    protected function resolveWildCardsPath(string $path, array $rules): array
    {
        if ($path === '*') {
            $result = [];

            foreach ($this->rules as $path => $pathRules) {
                $explodedPath               =   \explode('.', $path);
                $result[$explodedPath[0]]   =   \array_merge($pathRules, $rules);
            }

            return $result;
        }

        // Get all the paths that match with the wildcard path either completely or partially from left-to-right.
        $actualPaths = \preg_grep("#^{$path}$#", $this->input->keys());

        // If no matches found, we want to create at least one dummy path to perform validation if necessary.
        if (empty($actualPaths)) {
            $search         =   ['.*.', '.*', '*.'];
            $replace        =   ['.0.', '.0', '0.'];
            $modifiedPath   =   \str_replace($search, $replace, $path);

            return [$modifiedPath => $rules];
        }

        $wildCardParams         =   \explode('.', $path);
        $wildCardParamsLength   =   \count($wildCardParams);

        // Loop through the array of found paths & complete any path which is
        // incomplete when compared to the wildcard path.
        foreach ($actualPaths as $index => $actualPath) {
            $actualParams       =   \explode('.', $actualPath);
            $actualParamsLength =   \count($actualParams);

            // If the number of parameters in the wildcard path exceed the number of
            // parameters in the current actual path, we need to add the missing
            // parameters to the current path.
            if ($actualParamsLength < $wildCardParamsLength) {
                $actualParams = \array_replace($wildCardParams, $actualParams);

                $actualParams = \array_map(function ($param) {
                    if ($param === '*') {
                        return '0';
                    }

                    return $param;
                }, $actualParams);

                $actualParams           =   \implode('.', $actualParams);
                $actualPaths[$index]    =   $actualParams;

                continue;
            }

            // If number of parameters in the current path exceed the number of parameters
            // in the wildcard parameter path, we need to trim down current path
            // containing actual parameters.
            if ($actualParamsLength > $wildCardParamsLength) {
                $actualParams           =   \array_slice($actualParams, 0, $wildCardParamsLength);
                $actualParams           =   \implode('.', $actualParams);
                $actualPaths[$index]    =   $actualParams;

                continue;
            }
        }

        return \array_fill_keys($actualPaths, $rules);
    }

    /**
     * Obtain the instance for the rules provided in the string/callback format.
     *
     * @param   string|callable(string $field, mixed $value, \AdityaZanjad\Validator\Fluents\Input $input): bool    $rule
     * @param   string                                                                                              $field
     *
     * @throws  \Exception
     *
     * @return  null|\AdityaZanjad\Validator\Base\AbstractRule
     */
    protected function makeRuleInstanceFromString(string $rule, int $ruleIndex, string $field): ?AbstractRule
    {
        if (empty($rule)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule}] at the index [{$ruleIndex}] for field [{$field}] must not be empty.");
        }

        // Extract the important data from the given parameters to execute the validation rule.
        $rule           =   \explode(':', $rule);
        $ruleClassName  =   Rule::valueOf($rule[0]);

        if (\is_null($ruleClassName)) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule [{$ruleClassName}] at the index [{$ruleIndex}].");
        }

        if ($this->input->isNull($field) && !\in_array(RequisiteRule::class, class_implements($ruleClassName))) {
            return null;
        }

        // If there were no arguments passed to the validation rule.
        if (!isset($rule[1])) {
            return new $ruleClassName(...[]);
        }

        // If the rule was provided with the constructor arguments.
        $ruleArgs = \preg_split('/(?<!\\\\),/', $rule[1]);
        $ruleArgs = array_map(fn($arg) => \str_replace('\\,', ',', $arg), $ruleArgs);

        return new $ruleClassName(...$ruleArgs);
    }

    /**
     * Evaluate the instance rule & return its result.
     *
     * @param   \AdityaZanjad\Validator\Abstracts\AbstractRule|callable(string $field, mixed $value, \AdityaZanjad\Validator\Fluents\Input $input): bool|string.
     * @param   string
     * @param   mixed
     *
     * @throws  \Exception
     *
     * @return  null|\AdityaZanjad\Validator\Base\AbstractRule
     */
    protected function makeRuleInstanceFromObject($rule, int $index, string $field): ?AbstractRule
    {
        if ($this->input->isNull($field) && !$rule instanceof RequisiteRule) {
            return null;
        }

        if (\is_callable($rule)) {
            return new Callback($rule);
        }

        if (!$rule instanceof AbstractRule) {
            // TODO => Add a logic for detecting the wildcard field path.
            throw new Exception("[Developer][Exception]: The field {$field} has an invalid rule at the index [{$index}]");
        }

        return $rule;
    }

    /**
     * Split the comma-separated string arguments into an array of arguments.
     *
     * @param string $args
     *
     * @return array<int, string>
     */
    protected function splitStringifiedArguments(string $args)
    {
        // Split the arguments using regex.
        if (\function_exists('\\preg_split')) {
            // $args = \preg_split('/(?<!\\\\),/', $args);
            return array_map(fn($arg) => \str_replace('\\,', ',', $arg), \preg_split('/(?<!\\\\),/', $args));
        }

        $cleanedArgs    =   [];
        $buffer         =   '';
        $length         =   \strlen($args);
        $escaped        =   false;

        for ($i = 0; $i < $length; $i++) {
            $char = $args[$i];

            if ($escaped) {
                $buffer     .=  $char;
                $escaped    =   false;

                continue;
            }

            switch ($char) {
                case '\\':
                    $escaped = true;
                    continue 2;

                case ',':
                    $cleanedArgs[]  =   $buffer;
                    $buffer         =   '';
                    continue 2;

                default:
                    $buffer .= $char;
                    break;
            }
        }

        $cleanedArgs[] = $buffer;

        // Now, unescape escaped commas and backslashes
        return array_map(function ($arg) {
            return \str_replace(['\\,', '\\\\'], [',', '\\'], $arg);
        }, $cleanedArgs);
    }

    /**
     * Check whether the validation was successful or not.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->errors->isEmpty();
    }

    /**
     * Get an instance of the class that holds & manages the error messages.
     *
     * @return \AdityaZanjad\Validator\Fluents\Error
     */
    public function errors(): Error
    {
        return $this->errors;
    }
}
