<?php

namespace App\Controller;

use App\Form\Type\ProductType;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidadorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class EcommerceController extends AbstractController
{
    #[Route('', name: 'homepage')]
    public function showAll(ProductRepository $productRepository): Response
    {
        $all = $productRepository->findAll();

        return $this->render('/ecommerce/homepageClient.html.twig', [
            "product"=>$all,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/admin', name: 'homepage_admin')]
    public function showAllTable(ProductRepository $productRepository): Response
    {
        $all = $productRepository->findAll();

        return $this->render('/ecommerce/admin.html.twig', [
            "product"=>$all,
        ]);
    }

    #[Route('/product/new', name: 'product_new')]
    public function newProduct(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $product = $form->getData();

            $entityManager->persist($product);

            $entityManager->flush();

            return $this->redirectToRoute('homepage_admin');
        }

        return $this->renderForm('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/{id}', name: 'product_info')]

    public function infoProduct(ProductRepository $productRepository, Product $product): Response
    {   
        $all = $productRepository->findAll();
        $product = $productRepository->findOneBy(['id'=>$product->getId()]);

        return $this->render('/ecommerce/infoProduct.html.twig', [
            "product"     =>$product,
            "products"    =>$all,
            "id"          =>$product->getId(),
            "name"        =>$product->getName(),
            "artist"      =>$product->getBrand(),
            "description" =>$product->getDescription(),
            "image"       =>$product->getFaceImage(),
        ]);
    }

    #[Route('/product/{id}/update', name: 'product_update')]
    public function updateProduct(ManagerRegistry $doctrine, Request $request, Product $product): Response
    {
        $entityManager = $doctrine->getManager();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $product = $form->getData();

            $entityManager->persist($product);

            $entityManager->flush(); 

            return $this->redirectToRoute('homepage_admin');
        }

        return $this->renderForm('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('product/{id}/delete', name: 'product_delete')]
    public function delete(ManagerRegistry $doctrine, Product $product): Response {
        
        $entityManager = $doctrine->getManager();

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('homepage_admin');
    }
}