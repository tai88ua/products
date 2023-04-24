<?php

namespace App\Controller;

use App\Service\Product as ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\CategoryDto;
use App\Service\Category as CategoryService;

class CategoryController extends AbstractController
{
    /**
     * Category info
     *
     * desc.
     *
     * @Route("/api/category/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns category item",
     *     @Model(type=CategoryDto::class)
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The field used to category id",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="getCategory")
     */
    public function getCategory(int $id, CategoryService $categoryService): JsonResponse
    {
        $data = $categoryService->getCategory($id);
        return new JsonResponse($data, $data ? 200 : 404);
    }

    /**
     * Categories List
     *
     * get Categories List
     *
     * @Route("/api/categories", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns categories",
     *     @OA\JsonContent(
     *        type="object",
     *             @OA\Property(
     *                property="categories items",
     *                type="array",
     *                @OA\Items(ref=@Model(type=CategoryDto::class, groups={"full"}))
     *          )
     *     )
     * )
     * @OA\Tag(name="getCategories")
     */
    public function getCategories(CategoryService $categoryService): JsonResponse
    {
        $data = $categoryService->getCategories();
        return new JsonResponse($data, $data ? 200 : 404);
    }
}
