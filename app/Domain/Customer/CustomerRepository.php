<?php
declare(strict_types=1);

namespace App\Domain\Customer;

use App\Model\Database\Repository\AbstractRepository;

/**
 * @method Customer|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Customer|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Customer[] findAll()
 * @method Customer[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Customer>
 */
class CustomerRepository extends AbstractRepository
{
	public function findOneByEmail(string $email): ?Customer
	{
		return $this->findOneBy(['email' => $email]);
	}
}
