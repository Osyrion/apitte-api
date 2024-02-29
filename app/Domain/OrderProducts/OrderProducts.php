<?php
declare(strict_types=1);

namespace App\Domain\OrderProducts;

use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_products")
 */
class OrderProducts
{
	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="integer", nullable=TRUE, unique=false) */
	private int $orderId;

	/** @ORM\Column(type="integer", nullable=TRUE, unique=false) */
	private int $productId;

	public function __construct(int $orderId, int $productId) {
		$this->orderId = $orderId; $this->productId = $productId;
	}

	public function getOrderId(): int
	{
		return $this->orderId;
	}

	public function setOrderId(int $orderId): void
	{
		$this->orderId = $orderId;
	}

	public function getProductId(): int
	{
		return $this->productId;
	}

	public function setProductId(int $productId): void
	{
		$this->productId = $productId;
	}
}
