<?php

use function AdityaZanjad\Validator\Presets\validate;

require 'vendor/autoload.php';

$startTime = microtime(true);

$validator = validate($_POST, [
    'first_name'    =>  'required|string|min:1',
    'last_name'     =>  'required|string|min:3',
    'email'         =>  'required|email|min:1',
    'phone_number'  =>  'required|integer|min:1',
]);

?>

<!DOCTYPE html>
<html>
    <title>Validation Results</title>
    <body>
        <?= '<pre>'; ?>
        <?php print_r($validator->errors()->all()); ?>
        <?= "</pre>"; ?>
        <br />
        <br />
        <br />
        <?php
            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;
            echo "<span style=\"font-size: 15px;\"><strong>Execution Time:</strong> {$executionTime} seconds</span>";
        ?>
    </body>
</html>
