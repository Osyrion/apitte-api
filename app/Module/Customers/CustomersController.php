<?php declare(strict_types = 1);

namespace App\Module\Customers;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Request\CustomerReqDto;
use App\Domain\Customer\CustomerService;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use App\Model\Utils\Caster;
use App\Module\BasePubController;
use Doctrine\DBAL\Exception\DriverException;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/customers")
 * @Apitte\Tag("Customers")
 */
class CustomersController extends BasePubController
{
	public function __construct(public CustomerService $customerService)
	{}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get customer by id.
	 * ")
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 *      @Apitte\RequestParameter(name="id", in="path", type="int", description="User ID")
	 * })
	 */
	public function detail(ApiRequest $request): array
	{
		try {
			return $this->customerService->serialize(
				$this->customerService->findOne(Caster::toInt($request->getParameter('id')))
			);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('User not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

	/**
	 * @Apitte\Path("/")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 *        @Apitte\RequestParameter(name="limit", type="int", in="query", required=false, description="Data limit"),
	 *        @Apitte\RequestParameter(name="offset", type="int", in="query", required=false, description="Data offset")
	 * })
	 */
	public function index(ApiRequest $request): array
	{
		try {
			return array_map(function ($customer) { return $this->customerService->serialize($customer); },
				$this->customerService->findAll(
					Caster::toInt($request->getParameter('limit', 10)),
					Caster::toInt($request->getParameter('offset', 0))
				)
			);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Customer not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\CustomerReqDto")
	 */
	public function update(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var CustomerReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->customerService->update(Caster::toInt($request->getParameter('id')), $dto);

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot update customer')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("DELETE")
	 * @Apitte\RequestParameters({
	 *      @Apitte\RequestParameter(name="id", in="path", type="int", description="User ID")
	 * })
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$this->customerService->forceDelete(Caster::toInt($request->getParameter('id')));

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot delete customer')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\Path("/create")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\CustomerReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var CustomerReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->customerService->create($dto);

			return $response->withStatus(IResponse::S201_Created)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot create customer')
				->withPrevious($e);
		}
	}
}
