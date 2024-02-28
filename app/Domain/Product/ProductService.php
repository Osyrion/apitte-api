<?php declare(strict_types = 1);

namespace App\Domain\Product;

use App\Domain\Api\Request\ProductRequestDto;
use App\Domain\Api\Response\ProductResponseDto;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class ProductService
{
	public function __construct(private readonly EntityManagerDecorator $em)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return ProductResponseDto[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'ASC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->em->getRepository(Product::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = ProductResponseDto::from($entity);
		}

		return $result;
	}

	/**
	 * @return ProductResponseDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): ProductResponseDto
	{
		$entity = $this->em->getRepository(Product::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return ProductResponseDto::from($entity);
	}

	public function findOne(int $id): ProductResponseDto
	{
		return $this->findOneBy(['id' => $id]);
	}
	public function create(ProductRequestDto $product): Product
	{
		$product = new Product(
			$product->name,
			$product->value,
			$product->stock,
		);

		$this->em->persist($product);
		$this->em->flush($product);

		return $product;
	}
}
