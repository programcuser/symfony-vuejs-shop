<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();
        dd($productList);

        return $this->render('main/default/index.html.twig', [
            // 'controller_name' => 'DefaultController',
        ]);
    }

    // #[Route('/product-add', name: 'product_add_old')]
    // public function productAdd(Request $request): Response
    // {
    //     $product = new Product();
    //     $product->setTitle('Product ' . rand(1, 100));
    //     $product->setDescription('smth');
    //     $product->setPrice(10);
    //     $product->setQuantity(1);
    //     // $product->setCreatedAt(new \DateTimeImmutable());
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->persist($product);
    //     $entityManager->flush();
    //     return $this->redirectToRoute('homepage');
    // }

    #[Route('/edit-product/{id}', methods: ['GET', 'POST'], name: 'product_edit', requirements: ['id' => '\d+'])]
    #[Route('/add-product', methods: ['GET', 'POST'], name: 'product_add')]
    public function editProduct(Request $request, int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($id) {
            $product = $entityManager->getRepository(Product::class)->find($id);
        } else {
            $product = new Product();
        }
        $form = $this->createFormBuilder($product)
            ->add('title', TextType::class)
            ->add('price', NumberType::class)
            ->add('quantity', IntegerType::class)
            ->getForm();

        // dump($product->getTitle());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dd($data);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }
        // dd($product, $form);
        
        return $this->render('main/default/edit_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
