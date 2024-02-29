<?php
declare(strict_types = 1);

namespace App\Domain\Product;

use App\Domain\Api\Request\ProductReqDto;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class ProductService
{
	public function __construct(private readonly EntityManagerDecorator $entityManager)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return Product[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'ASC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->entityManager->getRepository(Product::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = $entity;
		}

		return $result;
	}

	/**
	 * @return Product[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): Product
	{
		$entity = $this->entityManager->getRepository(Product::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return $entity;
	}

	public function findOne(int $id): Product
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(ProductReqDto $dto): Product
	{
		$product = new Product(
			$dto->name, $dto->value, $dto->stock
		);
		$product->setCreatedAt();
		$product->setUpdatedAt();
		$this->entityManager->persist($product);
		$this->entityManager->flush($product);

		return $product;
	}

	public function update(int $id, ProductReqDto $dto): void
	{
		$product = $this->findOne($id);

		$product->setName($dto->name);
		$product->setValue($dto->value);
		$product->setStock($dto->stock);
		$product->setUpdatedAt();

		$this->entityManager->flush();
	}

	public function forceDelete(int $id): void
	{
		$product = $this->findOne($id);
		$this->entityManager->remove($product);
		$this->entityManager->flush();
	}

	public function serialize(Product $product): array
	{
		return [
			"name" => $product->getName(),
			"value" => $product->getValue(),
			"stock" => $product->getStock()
		];
	}
}
