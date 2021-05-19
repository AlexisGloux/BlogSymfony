<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BodyLongerThenTitle extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Le body doit être 2 fois plus long que le titre minimum (class de validation)';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
