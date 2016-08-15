<?php

namespace Test\App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter() {
		$router = new RouteList();
		$router[] = $front = new RouteList();

		$front[] = new Route('<presenter>[/<action>[/<id>]]', 'Homepage:default');

		return $router;
	}
}
