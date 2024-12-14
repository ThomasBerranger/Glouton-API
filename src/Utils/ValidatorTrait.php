<?php

namespace App\Utils;

use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ValidatorTrait
{
    private ValidatorInterface $validator;

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * @return array<string, string>
     */
    public function validate(object $object, string|GroupSequence|array|null $groups = null): array
    {
        $errors = $this->validator->validate($object, groups: $groups);

        return array_merge(...array_map(
            function (ConstraintViolationInterface $violation): array {
                return [$violation->getPropertyPath() => $violation->getMessage()];
            },
            iterator_to_array($errors)
        ));
    }
}
