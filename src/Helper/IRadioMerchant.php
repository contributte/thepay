<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 20.8.15
 * Time: 17:24
 */

namespace Trejjam\ThePay\Helper;

use Tp;

interface IRadioMerchant
{
	/**
	 * @return Tp\Helper\RadioMerchant
	 */
	function create();
}
