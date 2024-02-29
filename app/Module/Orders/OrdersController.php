<?php declare(strict_types = 1);

namespace App\Module\Orders;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Request\OrderReqDto;
use App\Domain\Order\OrderService;
use App\Domain\OrderProducts\OrderProductService;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use App\Model\Utils\Caster;
use App\Module\BasePubController;
use Doctrine\DBAL\Exception\DriverException;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/orders")
 * @Apitte\Tag("Orders")
 */
class OrdersController extends BasePubController
{
	public function __construct(
		private readonly OrderService $orderService,
		private readonly OrderProductService $orderProductService,
	) {}

	/**
	 * @Apitte\Path("/create")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\OrderReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var OrderReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->orderService->create($dto);

			return $response->withStatus(IResponse::S201_Created)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot create order')
				->withPrevious($e);
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
			return array_map(function ($order) { return $this->orderService->serialize($order,
				$this->orderProductService->findAllBy(Caster::toInt($order->getId()))); },
				$this->orderService->findAll(
					Caster::toInt($request->getParameter('limit', 10)),
					Caster::toInt($request->getParameter('offset', 0))
				)
			);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Product not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get order by id.
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
			return $this->orderService->serialize(
				$this->orderService->findOne(Caster::toInt($request->getParameter('id'))),
				$this->orderProductService->findAllBy(Caster::toInt($request->getParameter('id')))
			);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Product not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\OrderReqDto")
	 */
	public function update(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var OrderReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->orderService->update(Caster::toInt($request->getParameter('id')), $dto);

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot update product')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("DELETE")
	 * @Apitte\RequestParameters({
	 *      @Apitte\RequestParameter(name="id", in="path", type="int", description="Order ID")
	 * })
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$this->orderService->forceDelete(Caster::toInt($request->getParameter('id')));

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot delete product')
				->withPrevious($e);
		}
	}
}
