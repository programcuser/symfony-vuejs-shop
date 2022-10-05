<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductImageManager
     */
    private $productImageManager;

    /**
     * @var string
     */
    private $productImagesDir;

    public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager, string $productImagesDir)
    {
        $this->entityManager = $entityManager;
        $this->productImageManager = $productImageManager;
        $this->productImagesDir = $productImagesDir;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @param Product $product
     */
    public function save(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function remove()
    {

    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductImagesDir(Product $product)
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    /**
     * @param Product $product
     * @param string|null $tempImageFilename
     * @return Product
     */
    public function updateProductImages(Product $product, string $tempImageFilename = null): Product
    {
        if (!$tempImageFilename) {
            return $product;
        }

        $productDir = $this->getProductImagesDir($product);

        $productImage = $this->productImageManager->saveImageForProduct($productDir, $tempImageFilename);
        // dd($productImage);
        $productImage->setProduct($product);
        $product->addProductImage($productImage);

        return $product;
    }
}
