<?php declare(strict_types = 1);

namespace App\Domain\Customer;

use App\Domain\Api\Request\CustomerReqDto;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class CustomerService
{
	public function __construct(private readonly EntityManagerDecorator $entityManager)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return Customer[]
	 */
	public function findBy(
		array $criteria = [],
		array $orderBy = ['id' => 'DESC'],
		int $limit = 10,
		int $offset = 0
	): array {
		$entities = $this->entityManager->getRepository(Customer::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = $entity;
		}

		return $result;
	}

	/**
	 * @return Customer[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'DESC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): Customer
	{
		$entity = $this->entityManager->getRepository(Customer::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return $entity;
	}

	public function findOne(int $id): Customer
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(CustomerReqDto $dto): Customer
	{
		$customer = new Customer(
			$dto->firstName, $dto->lastName, $dto->email, $dto->telephone
		);
		$customer->setCreatedAt();
		$customer->setUpdatedAt();

		$this->entityManager->persist($customer);
		$this->entityManager->flush();

		return $customer;
	}

	public function update(int $id, CustomerReqDto $dto): void
	{
		$customer = $this->findOne($id);

		$customer->setFirstName($dto->firstName);
		$customer->setLastName($dto->lastName);
		$customer->setEmail($dto->email);
		$customer->setTelephone($dto->telephone);
		$customer->setUpdatedAt();

		$this->entityManager->flush();
	}

	public function forceDelete(int $id): void
	{
		$customer = $this->findOne($id);

		$this->entityManager->remove($customer);
		$this->entityManager->flush();
	}

	public function serialize(Customer $customer): array
	{
		return [
			"fullname" => $customer->getFullName(),
			"email" => $customer->getEmail(),
			"telephone" => $customer->getTelephone()
		];
	}
}
