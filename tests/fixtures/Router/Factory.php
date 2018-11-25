<?php declare(strict_types = 1);

namespace Tests\Router;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Factory
{

	public static function createRouter(): RouteList
	{
		$router = new RouteList();
		$router[] = $front = new RouteList();

		$front[] = new Route('<presenter>[/<action>[/<id>]]', 'Homepage:default');

		return $router;
	}

}
