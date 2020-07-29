<?php
namespace App\Controller;

use \Core\Controller\Controller;


use App\Controller\PaginatedQueryAppController;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->loadModel('category');
        $this->loadModel('post');
    }
    public function all()
    {

        $paginatedQuery = new PaginatedQueryAppController(
            $this->category,
            $this->generateUrl('categories')
        );

        $categories = $paginatedQuery->getItems();
        $title = "Catégories";

        return $this->render(
            "category/all",
            [
                "title" => $title,
                "categories" => $categories,
                "paginate" => $paginatedQuery->getNavHTML()
            ]
        );
    }

    public function show(string $slug, int $id)
    {
        $category = $this->category->find($id);

        if (!$category) {
            throw new Exception('Aucune categorie ne correspond à cet ID');
        }

        if ($category->getSlug() !== $slug) {
            $url = $this->generateUrl('category', ['id' => $id, 'slug' => $category->getSlug()]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }

        $title = 'categorie : ' . $category->getName();

        $uri = $this->generateUrl("category", ["id" => $category->getId(), "slug" => $category->getSlug()]);

        $paginatedQuery = new PaginatedQueryAppController(
            $this->post,
            $uri
        );

        $postById = $paginatedQuery->getItemsInId($id);

        return $this->render(
            "category/show",
            [
                "title" => $title,
                "posts" => $postById,
                "paginate" => $paginatedQuery->getNavHTML()
            ]
        );
    }
}
