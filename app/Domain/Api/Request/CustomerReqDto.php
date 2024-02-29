<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class CustomerReqDto
{

	/**
	 * @Assert\NotBlank
	 * @Assert\Email
	 */
	public string $email;

	/** @Assert\NotBlank */
	public string $firstName;

	/** @Assert\NotBlank */
	public string $lastName;

	/** @Assert\NotBlank */
	public string $telephone;
}
