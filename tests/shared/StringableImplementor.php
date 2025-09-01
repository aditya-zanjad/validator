<?php

class StringableImplementor implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return "Stringable: " . $this->value;
    }
}
