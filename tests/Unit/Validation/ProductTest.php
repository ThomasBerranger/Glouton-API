<?php

namespace App\Tests\Unit\Validation;

use App\Entity\ExpirationDate;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductTest extends BaseTest
{
    public function testScannedProductDeserializationGroup(): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct
            ->setOwner($this->getUser(User::USER))
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('01/01/2025'))
            ->setAddedToListAt(new \DateTime('01/01/2025'))
            ->setNutriscore('A')
            ->setEcoscore(1)
            ->setNovagroup(4)
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01/01/2025')));

        $violations = $validator->validate($scannedProduct, groups: ['create']);

        $this->assertSame(['barcode', 'name'], array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, $violations->getIterator()->getArrayCopy()));
    }

    public function testCustomProductDeserializationGroup(): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $customProduct = new CustomProduct();
        $customProduct
            ->setOwner($this->getUser(User::USER))
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('01/01/2025'))
            ->setAddedToListAt(new \DateTime('01/01/2025'))
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01/01/2025')));

        $violations = $validator->validate($customProduct, groups: ['create']);

        $this->assertSame(['name'], array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, $violations->getIterator()->getArrayCopy()));
    }
}
