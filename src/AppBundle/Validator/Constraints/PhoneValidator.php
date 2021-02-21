<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		if (!$constraint instanceof Phone) {
			throw new UnexpectedTypeException($constraint, Phone::class);
		}

		if (null === $value || '' === $value) {
			return;
		}

		if (!preg_match('/^[0-9]+$/', $value, $matches)) {
			$this->context->buildViolation($constraint->message)
				->addViolation();
		}
	}
}
