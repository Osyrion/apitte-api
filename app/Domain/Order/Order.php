<?php
declare(strict_types=1);

namespace App\Domain\Order;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks
 */
class Order extends AbstractEntity
{
	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="integer", nullable=FALSE, unique=false) */
	private int $customerId;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $orderState;

	/** @ORM\Column(type="float", nullable=FALSE, unique=false) */
	private float $price;


	public function __construct(int $customerId, string $orderState, float $price)
	{
		$this->customerId = $customerId;
		$this->orderState = $orderState;
		$this->price = $price;
	}

	public function getCustomerId(): int
	{
		return $this->customerId;
	}

	public function setCustomerId(int $customerId): void
	{
		$this->customerId = $customerId;
	}

	// Getter and setter for orderState
	public function getOrderState(): ?string
	{
		return $this->orderState;
	}

	public function setOrderState(string $orderState): void
	{
		$this->orderState = $orderState;
	}

	// Getter and setter for price
	public function getPrice(): ?float
	{
		return $this->price;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}
}
