<?php declare(strict_types = 1);

namespace App\Module\public;

use Apitte\Core\Annotation\Controller as Apitte;
use App\Module\BasePubController;

/**
 * @Apitte\Path("/public")
 * @Apitte\Id("public")
 */
abstract class BasePublicController extends BasePubController
{

}
