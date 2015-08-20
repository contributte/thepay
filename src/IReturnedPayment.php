<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 20.8.15
 * Time: 11:44
 */

namespace Trejjam\ThePay;


interface IReturnedPayment
{
	/**
	 * @return ReturnedPayment
	 */
	function create();
}
