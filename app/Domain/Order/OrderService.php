<?php
declare(strict_types = 1);

namespace App\Domain\Order;

use App\Domain\Api\Request\OrderReqDto;
use App\Domain\Customer\CustomerService;
use App\Domain\OrderProducts\OrderProducts;
use App\Domain\OrderProducts\OrderProductService;
use App\Domain\Product\ProductService;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class OrderService
{
	public function __construct(
		private readonly EntityManagerDecorator $entityManager,
		private readonly CustomerService $customerService,
		private readonly ProductService $productService,
		private readonly OrderProductService $orderProductService,
	) {}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return Order[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'ASC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->entityManager->getRepository(Order::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = $entity;
		}

		return $result;
	}

	/**
	 * @return Order[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): Order
	{
		$entity = $this->entityManager->getRepository(Order::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return $entity;
	}

	public function findOne(int $id): Order
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(OrderReqDto $dto): Order
	{
		$customer = $this->customerService->findOne($dto->customerId);
		$order = new Order(
			$customer->getId(), $dto->orderState, $dto->price
		);
		$order->setCreatedAt();
		$order->setUpdatedAt();

		$this->entityManager->persist($order);
		$this->entityManager->flush();

		$products = array_map(function ($product) { return $this->productService->findOne($product); }, $dto->products);
		$this->orderProductService->saveProducts($order->getId(), $products);

		return $order;
	}


	public function update(int $orderId, OrderReqDto $dto): void
	{
		$order = $this->findOne($orderId);
		$customer = $this->customerService->findOne($dto->customerId);
		$products = array_map(function ($product) { return $this->productService->findOne($product); }, $dto->products );

		$order->setCustomerId($customer->getId());
		$order->setOrderState($dto->orderState);
		$order->setPrice($dto->price);

		$this->entityManager->flush();

		$this->orderProductService->update($order->getId(), $products);
	}

	public function forceDelete(int $id): void
	{
		$order = $this->findOne($id);
		$this->entityManager->remove($order);
		$this->entityManager->flush();
	}

	public function serialize(Order $order, array $products): array
	{
		return [
			'customer' => $order->getCustomerId(),
			'orderState' => $order->getOrderState(),
			'price' => $order->getPrice(),
			'products' => array_map(function ($product)
				{ return $this->productService->serialize($this->productService->findOne($product->getProductId())); }, $products)
		];
	}
}
