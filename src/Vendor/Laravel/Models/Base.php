<?php

/**
 * Part of the Tracker package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Tracker
 * @author     Antonio Carlos Ribeiro @ PragmaRX
 * @license    BSD License (3-clause)
 * @copyright  (c) 2013, PragmaRX
 * @link       http://pragmarx.com
 */

namespace PragmaRX\Tracker\Vendor\Laravel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Symfony\Component\Console\Application;

class Base extends Eloquent {

	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);

		$this->setConnection($this->getConfig()->get('connection'));
	}

	public function getConfig()
	{
		if ($GLOBALS["app"] instanceof Application)
		{
			return app()->make('tracker.config');
		}

		return $GLOBALS["app"]["tracker.config"];
	}

	public function scopePeriod($query, $minutes, $alias = '')
	{
		$alias = $alias ? "$alias." : '';

		return $query
				->where($alias.'updated_at', '>=', $minutes->getStart())
				->where($alias.'updated_at', '<=', $minutes->getEnd());
	}

	public function scopePeriodic($query, $start, $end, $alias = '')
	{
		$alias = $alias ? "$alias." : '';

		return $query
				->where($alias.'updated_at', '>=', $start)
				->where($alias.'updated_at', '<=', $end);
	}

}
