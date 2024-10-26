<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ValidatorTrait
{
    protected readonly ValidatorInterface $validator;

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function validate(object $object): array
    {
        $errors = $this->validator->validate($object);

        return array_merge(...array_map(
            function (ConstraintViolation $violation) {
                return [$violation->getPropertyPath() => $violation->getMessage()];
            },
            $errors->getIterator()->getArrayCopy()
        ));
    }
}
