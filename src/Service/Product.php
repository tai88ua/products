<?php

namespace App\Service;

use App\Dto\ProductDto;
use App\Entity\Category;
use App\Entity\Product as ProductModel;
use Doctrine\ORM\EntityManagerInterface;

class Product
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProduct(int $productId) : ?ProductDto
    {
        $productRepo = $this->entityManager->getRepository(ProductModel::class);
        $productModel = $productRepo->find($productId);

        if (!$productModel) {
            return null;
        }

        return $this->makeProductDto($productModel);
    }

    private function makeProductDto(ProductModel $productModel) : ProductDto
    {
        $dto = new ProductDto();
        $dto->setId($productModel->getId());
        $dto->setName($productModel->getName());
        $dto->setDescription($productModel->getDescription());
        $dto->setPrice($productModel->getPrice());
        $dto->setImageLink($productModel->getImageLink());
        $dto->setCategory($productModel->getCategory()?->getName());
        return $dto;
    }

    public function getProducts() : array
    {
        $productRepo = $this->entityManager->getRepository(ProductModel::class);
        $items =  $productRepo->findAll();
        $data = [];
        foreach ($items as $item) {
            $data[] = $this->makeProductDto($item);
        }

        return $data;
    }

    public function saveOrUpdate(array $productsDpo): void
    {
        $productRepo = $this->entityManager->getRepository(ProductModel::class);
        $categoryRepo = $this->entityManager->getRepository(Category::class);

        /** @var ProductDto $productDpo */
        foreach ($productsDpo as $productDpo) {
            $product = $productRepo->findOneBy(['name' => $productDpo->getName()]);
            if (!$product) {
                $product = new ProductModel();
                $product->setName($productDpo->getName());
            }
            $product->setPrice($productDpo->getPrice());
            $product->setDescription($productDpo->getDescription());
            $product->setImageLink($productDpo->getImageLink());

            $category = $categoryRepo->findOneBy(['name' => $productDpo->getCategory()]);
            if (!$category) {
                $category = new Category();
                $category->setName($productDpo->getCategory());
                $this->entityManager->persist($category);
                $this->entityManager->flush();
            }

            $product->setCategory($category);
            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();
    }

}