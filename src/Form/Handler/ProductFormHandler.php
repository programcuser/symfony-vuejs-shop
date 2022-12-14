<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\Form\Form;

class ProductFormHandler
{
    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * @var FileSaver
     */
    private $fileSaver;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->productManager = $productManager;
        $this->fileSaver = $fileSaver;
    }

    public function processEditForm(Product $product, Form $form)
    {
        $this->productManager->save($product);

        $newImageFile = $form->get('newImage')->getData();

        $tempImageFilename = $newImageFile
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;
        
        $this->productManager->updateProductImages($product, $tempImageFilename);

        // TODO: ADD A NEW IMAGE WITH DIFFERENT SIZES TO THE PRODUCT
        // 1. Save product's changes (+)
        // 2. Save uploaded file into temp folder (+)

        // 3. Work with Product (addProductImage) and ProductImage
        // 3.1 Get path of folder with images of product (+)

        // 3.2 Work with ProductImage
        // 3.2.1 Resize and save image into folder (BIG, MIDDLE, SMALL) (+)
        // 3.2.2 Create ProductImage and return it to Product (+)

        // 3.3 Save Product with new ProductImage (+)

        // dd($product, $form->get('newImage')->getData()); // DUMP
        // $entityManager = $this->getDoctrine()->getManager();

        
        // $this->entityManager->flush();
        $this->productManager->save($product);
        // dd($product);
        return $product;
    }
}
