<?php
declare(strict_types=1);

namespace App\Domain\Customer;

use App\Domain\Order\Order;
use App\Model\Database\Entity\TId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="CustomerRepository")
 * @ORM\Table(name="`customers`")
 * @ORM\HasLifecycleCallbacks
 */
class Customer
{
	use TId;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $firstName;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $lastName;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE) */
	private string $email;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE) */
	private string $telephone;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="customer")
	 */
	private $orders;

	public function __construct(string $firstName, string $lastName, string $email, string $telephone)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->telephone = $telephone;
		$this->orders = new ArrayCollection();
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function getFullName(): string
	{
		return $this->getFirstName() . ' ' . $this->getLastName();
	}

	public function getTelephone(): string
	{
		return $this->telephone;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function changeName(string $lastName, string $firstName = null): void
	{
		if ($firstName !== null) {
			$this->setFirstName($firstName);
		}
		$this->setLastName($lastName);
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function setTelephone(string $telephone): void
	{
		$this->telephone = $telephone;
	}

	/**
	 * @return Collection<int, Order>
	 */
	public function getOrders(): Collection
	{
		return $this->orders;
	}

	public function addOrder(Order $order): self
	{
		if (!$this->orders->contains($order)) {
			$this->orders[] = $order;
			$order->setCustomer($this);
		}

		return $this;
	}
}
