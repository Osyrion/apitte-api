<?php
declare(strict_types = 1);

namespace App\Domain\OrderProducts;

use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class OrderProductService
{
	public function __construct(private readonly EntityManagerDecorator $entityManager)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return OrderProducts[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'ASC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->entityManager->getRepository(OrderProducts::class)
			->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = $entity;
		}

		return $result;
	}

	/**
	 * @return OrderProducts[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): OrderProducts
	{
		$entity = $this->entityManager->getRepository(OrderProducts::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return $entity;
	}

	public function findOne(int $id): OrderProducts
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function findAllBy(int $orderId): array
	{
		return $this->findBy(['orderId' => $orderId]);
	}

	public function saveProducts(int $orderId, array $products): void
	{
		foreach ($products as $product) {
			$orderProducts = new OrderProducts($orderId, $product->getId());
			$orderProducts->setCreatedAt();
			$orderProducts->setUpdatedAt();

			$this->entityManager->persist($orderProducts);
			$this->entityManager->flush();
		}
	}

	public function update(int $orderId, array $products): void
	{
		$orderProducts = $this->findOneBy(['orderId' => $orderId]);
		foreach ($products as $product) {
			$orderProducts->setOrderId($orderId);
			$orderProducts->setProductId($product->getId());

			$this->entityManager->flush();
		}
	}
}
