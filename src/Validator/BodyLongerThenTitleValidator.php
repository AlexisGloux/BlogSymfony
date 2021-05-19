<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BodyLongerThenTitleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\BodyLongerThenTitle */

        if (null === $value || '' === $value) {
            return;
        }

        if (strlen($value->getBody()) >= 2 * strlen($value->getTitle()))
            return;

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
