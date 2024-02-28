<?php
declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Customer\Customer;
use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Order extends AbstractEntity
{
	use TId;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $orderState;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2)
	 */
	private float $price;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="orders")
	 */
	private $products;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private Customer $customer;

	public function __construct(string $orderState, float $price, Customer $customer)
	{
		$this->orderState = $orderState;
		$this->price = $price;
		$this->customer = $customer;
		$this->products = new ArrayCollection();
	}

	public function getOrderState(): string
	{
		return $this->orderState;
	}

	public function setOrderState(string $orderState): self
	{
		$this->orderState = $orderState;

		return $this;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function setPrice(float $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getCustomer(): Customer
	{
		return $this->customer;
	}

	public function setCustomer(Customer $customer): self
	{
		$this->customer = $customer;

		return $this;
	}
}
