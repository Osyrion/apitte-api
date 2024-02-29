<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class ProductReqDto
{
	/**
	 * @Assert\NotBlank
	 */
	public string $name;

	/**
	 * @Assert\NotBlank
	 */
	public float $value;

	/**
	 * @Assert\NotBlank
	 */
	public int $stock;
}
