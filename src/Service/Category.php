<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category as CategoryModel;
use App\Dto\CategoryDto as CategoryDto;

class Category
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function makeCategoryDto(CategoryModel $categoryModel) : CategoryDto
    {
        $dto = new CategoryDto();
        $dto->setId($categoryModel->getId());
        $dto->setName($categoryModel->getName());
        return $dto;
    }


    public function getCategory(int $id): ?CategoryDto
    {
        $categoryRepo = $this->entityManager->getRepository(CategoryModel::class);
        $categoryModel = $categoryRepo->find($id);

        if (!$categoryModel) {
            return null;
        }

        return $this->makeCategoryDto($categoryModel);
    }

    public function getCategories() : array
    {
        $categoryRepo = $this->entityManager->getRepository(CategoryModel::class);
        $items = $categoryRepo->findAll();

        $data = [];
        foreach ($items as $item) {
            $data[] = $this->makeCategoryDto($item);
        }

        return $data;
    }
}
