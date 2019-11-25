<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CustomLength extends Constraint
{
    public $message = 'The string "{{ string }}" is out of allowed range';
}