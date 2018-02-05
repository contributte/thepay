<?php
declare(strict_types=1);

namespace Tests\Router;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class Factory
{
	public static function createRouter() : RouteList
	{
		$router = new RouteList;
		$router[] = $front = new RouteList;

		$front[] = new Route('<presenter>[/<action>[/<id>]]', 'Homepage:default');

		return $router;
	}
}

