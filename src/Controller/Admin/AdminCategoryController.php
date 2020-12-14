<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminCategoryController.
 * 
 * @Route("/admin/categories")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa070819999@gmail.com>
 */
class AdminCategoryController extends BaseController
{
    /** @var CategoryRepository */
    private CategoryRepository $categoryRepository;

    /**
     * AdminCategoryController constructor.
     *
     * @param EntityManagerInterface $em
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        parent::__construct($em);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Permet de lister toutes les categories.
     * 
     * @Route("/", name="admin_category_index", methods={"POST","GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    /**
     * Permet de creer une nouvelle categorie.
     * 
     * @Route("/new", name="admin_category_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($category)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le catégorie {$category->getTitle()} a bien été enregistrée"
                );
                return $this->redirectToRoute('admin_category_index');
            }
            return $this->redirectToRoute('admin_category_new');
        }
        return $this->render('admin/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une categorie.
     * 
     * @Route("/{id}/edit", name="admin_category_edit", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($category)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le catégorie {$category->getTitle()} a bien été modifiée"
                );
                return $this->redirectToRoute('admin_category_index');
            }
            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }
        return $this->render('admin/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une categorie.
     * 
     * @Route("/{id}/delete", name="admin_category_delete")
     *
     * @param Category $category
     * @return Response
     */
    public function delete(Category $category): Response
    {
        if ($this->remove($category)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Le catégorie {$category->getTitle()} a bien été supprimée"
            );
        }
        return $this->redirectToRoute('admin_category_index');
    }
}
