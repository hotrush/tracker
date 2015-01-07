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

use Carbon\Carbon;

class Event extends Base {

	protected $table = 'tracker_events';

	protected $fillable = array(
		'name',
	);

	public function allInThePeriod($minutes, $result)
	{
		$query =
			$this
				->select(
					'tracker_events.id',
					'tracker_events.name',
					$this->getConnection()->raw('count(tracker_events_log.id) as total')
				)
				->from('tracker_events')
				->period($minutes, 'tracker_events_log')
				->join('tracker_events_log', 'tracker_events_log.event_id', '=', 'tracker_events.id')
				->groupBy('tracker_events.id', 'tracker_events.name')
				->orderBy('total', 'desc');

		if ($result)
		{
			return $query->get();
		}

		return $query;
	}

	public function customEventForPeriod($event, $start, $end, $result)
	{
		$query =
			$this
				->select(
					$this->getConnection()->raw('DATE(tracker_events_log.created_at) as date'),
					$this->getConnection()->raw('count(tracker_events_log.id) as total')
				)
				->from('tracker_events')
				->join('tracker_events_log', 'tracker_events_log.event_id', '=', 'tracker_events.id')
				->where('tracker_events.name','=',$event)
				->periodic($start, $end, 'tracker_events_log')
				->groupBy(
					$this->getConnection()->raw('DATE(tracker_events_log.created_at)')
				)
				->orderBy('date');

		if ($result)
		{
			return $query->get();
		}

		return $query;
	}

}
