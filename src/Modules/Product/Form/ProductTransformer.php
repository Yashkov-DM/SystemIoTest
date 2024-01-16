<?php

declare(strict_types=1);

namespace App\Modules\Product\Form;

use App\Modules\Product\Entity\Product;
use App\Modules\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

readonly class ProductTransformer implements DataTransformerInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param Product|null $value
     */
    public function transform($value): ?int
    {
        return $value?->getId();
    }

    /**
     * @param string|null $value
     */
    public function reverseTransform($value): ?Product
    {
        if (!$value) {
            return null;
        }

        /** @var Product $product */
        if (!$product = $this->productRepository->findOneBy(['id' => $value])) {
            throw new TransformationFailedException(sprintf('Product with id "%s" does not exist!', $value));
        }

        return $product;
    }
}
