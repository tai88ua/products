<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\ProductDto;
use App\Service\Product as ProductService;
class ProductController extends AbstractController
{
    /**
     * Product info
     *
     * desc.
     *
     * @Route("/api/product/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns product item",
     *     @Model(type=ProductDto::class)
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The field used to product id",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="getProduct")
     */
    public function getProduct(int $id, ProductService $productService): JsonResponse
    {
        $data = $productService->getProduct($id);
        return new JsonResponse($data, $data ? 200 : 404);
    }

    /**
     * Products List
     *
     * get Products List
     *
     * @Route("/api/products", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns product",
     *     @OA\JsonContent(
     *        type="object",
     *             @OA\Property(
     *                property="Product items",
     *                type="array",
     *                @OA\Items(ref=@Model(type=ProductDto::class, groups={"full"}))
     *          )
     *     )
     * )
     * @OA\Tag(name="getProductList")
     */
    public function getProductList(ProductService $productService): JsonResponse
    {
        $data = $productService->getProducts();
        return new JsonResponse($data, $data ? 200 : 404);
    }
}
