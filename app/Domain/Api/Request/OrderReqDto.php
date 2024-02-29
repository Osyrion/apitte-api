<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderReqDto
{

	/**
	 * @Assert\NotBlank
	 */
	public int $customerId;

	/** @Assert\NotBlank */
	public string $orderState;

	/**
	 * @Assert\NotBlank
	 */
	public float $price;

	/**
	 * @Assert\NotBlank
	 */
	public array $products;

}
