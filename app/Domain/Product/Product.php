<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ProductRepository")
 * @ORM\Table(name="`products`")
 * @ORM\HasLifecycleCallbacks
 */
class Product
{
	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="float", nullable=FALSE, unique=false) */
	private float $value;

	/** @ORM\Column(type="integer", nullable=TRUE, unique=false) */
	private int $stock;


	public function __construct(string $name, float $value, int $stock)
	{
		$this->name = $name;
		$this->value = $value;
		$this->stock = $stock;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getValue(): float
	{
		return $this->value;
	}

	public function getStock(): int
	{
		return $this->stock;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setValue(float $value): void
	{
		$this->value = $value;
	}

	public function setStock(int $stock): void
	{
		$this->stock = $stock;
	}
}
