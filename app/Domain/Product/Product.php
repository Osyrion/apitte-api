<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Model\Database\Entity\TId;

class Product
{
	use TId;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="float", length=255, nullable=FALSE, unique=false) */
	private float $value;

	/** @ORM\Column(type="integer", length=255, nullable=TRUE, unique=false) */
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
