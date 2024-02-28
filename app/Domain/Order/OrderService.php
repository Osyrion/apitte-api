<?php declare(strict_types = 1);

namespace App\Domain\Order;

use App\Domain\Api\Services\OrderResultDto;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class OrderService
{
	public function __construct(private readonly EntityManagerDecorator $em)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return OrderResultDto[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'ASC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->em->getRepository(Order::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = OrderResultDto::from($entity);
		}

		return $result;
	}

	/**
	 * @return OrderResultDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): OrderResultDto
	{
		$entity = $this->em->getRepository(Order::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return OrderResultDto::from($entity);
	}

	public function findOne(int $id): OrderResultDto
	{
		return $this->findOneBy(['id' => $id]);
	}
}
