<?php
declare(strict_types = 1);

namespace App\Module\Products;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Request\ProductReqDto;
use App\Domain\Product\ProductService;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use App\Model\Utils\Caster;
use App\Module\BasePubController;
use Doctrine\DBAL\Exception\DriverException;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/products")
 * @Apitte\Tag("Products")
 */
class ProductsController extends BasePubController
{
	public function __construct(
		private readonly productService $productService,
	) {}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get product by id.
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
			return $this->productService->serialize(
				$this->productService->findOne(Caster::toInt($request->getParameter('id')))
			);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Product not found')
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
			return array_map(function ($product) { return $this->productService->serialize($product); },
				$this->productService->findAll(
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
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\ProductReqDto")
	 */
	public function update(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var ProductReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->productService->update(Caster::toInt($request->getParameter('id')), $dto);

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
	 *      @Apitte\RequestParameter(name="id", in="path", type="int", description="Product ID")
	 * })
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$this->productService->forceDelete(Caster::toInt($request->getParameter('id')));

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot delete product')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\Path("/create")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\ProductReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var ProductReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->productService->create($dto);

			return $response->withStatus(IResponse::S201_Created)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot create product')
				->withPrevious($e);
		}
	}
}
