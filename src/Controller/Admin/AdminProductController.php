<?php

namespace App\Controller\Admin;

use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProductController.
 * 
 * @Route("/admin/products")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa070819999@gmail.com>
 */
class AdminProductController extends BaseController
{
    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /**
     * AdminProductController constructor.
     *
     * @param EntityManagerInterface $em
     * @param ProductRepository $productRepository
     */
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository)
    {
        parent::__construct($em);
        $this->productRepository = $productRepository;
    }

    /**
     * Permet de lister tous les produits.
     * 
     * @Route("/", name="admin_product_index", methods={"POST","GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $this->productRepository->findAll()
        ]);
    }


    /**
     * Permet de creer un nouveau produit.
     * 
     * @Route("/new", name="admin_product_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setAuthor($this->getUser());

            $files = $form->get('images')->getData();
            $this->uploadFiles($files, $product);

            if ($this->save($product)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le produit a bien été enregistré"
                );
                return $this->redirectToRoute('admin_product_index');
            }
            return $this->redirectToRoute('admin_product_new');
            dd($product);
        }
        return $this->render('admin/product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un nouveau produit.
     * 
     * @Route("/{id}/edit", name="admin_product_edit", methods={"POST","GET"})
     *
     * @param Product $product
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setAuthor($this->getUser());

            $files = $form->get('images')->getData();
            $this->uploadFiles($files, $product);

            if ($this->save($product)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le produit a bien été modifié"
                );
                return $this->redirectToRoute('admin_product_index');
            }
            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }
        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un produit.
     * 
     * @Route("/{id}/delete", name="admin_product_delete")
     *
     * @param Product $product
     * @return Response
     */
    public function delete(Product $product): Response
    {
        if ($this->remove($product)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Le produit a bien été supprimé"
            );
        }
        return $this->redirectToRoute('admin_product_index');
    }
}
