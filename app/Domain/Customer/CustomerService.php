<?php declare(strict_types = 1);

namespace App\Domain\Customer;

use App\Domain\Api\Request\CustomerRequestDto;
use App\Domain\Api\Response\CustomerResponseDto;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class CustomerService
{
	public function __construct(private readonly EntityManagerDecorator $em)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return CustomerResponseDto[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'ASC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->em->getRepository(Customer::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = CustomerResponseDto::from($entity);
		}

		return $result;
	}

	/**
	 * @return CustomerResponseDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): Customer
	{
		$entity = $this->em->getRepository(Customer::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return CustomerResponseDto::from($entity);
	}

	public function findOne(int $id): Customer
	{
		return $this->findOneBy(['id' => $id]);
	}
}
