<?php

namespace App\Controller\Front;

use App\Controller\BaseController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController.
 * 
 * @Route("/products")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class ProductController extends BaseController
{
    /** @var ProductRepository */
    protected ProductRepository $productRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Permet de lister les produits.
     * 
     * @Route("/", name="product_index", methods={"POST","GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $this->productRepository->findAll()
        ]);
    }
}
