<?php

namespace iutnc\deefy\exception;

use Exception;

// InvalidPropertyNameException is an exception that is thrown when a property name is invalid
class InvalidPropertyNameException extends Exception
{
    public function __construct(string $property)
    {
        $safeValue = htmlspecialchars($property, ENT_QUOTES, 'UTF-8');
        parent::__construct("Invalid property value: $safeValue");
    }
}