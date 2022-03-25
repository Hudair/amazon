<?php

use Carbon\Carbon;
use App\Models\Inventory;
use App\Helpers\ListHelper;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_flash_deals')) {
	/**
	 * Get Flash Deals
	 * @return array | null
	 */
	function get_flash_deals()
	{
		$deals = get_from_option_table('flashdeal_items', []);

		if (empty($deals)) {
			return Null;
		}
		// Return null if not in valid time period
		if ($deals['start_time'] > Carbon::now() || Carbon::now() > $deals['end_time']) {
			return Null;
		}

		$items = [];

		if (!empty($deals['listings'])) {
			$items['listings'] = Inventory::available()
				->whereIn('id', $deals['listings'])
				->select(ListHelper::common_select_attr('inventory'))
				// ->withCount([
				// 	'feedbacks as ratings' => function ($q2) {
				// 		$q2->select(DB::raw('avg(rating)'));
				// 	}
				// ])
				->with([
					'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
					// 'feedbacks:rating,feedbackable_id,feedbackable_type',
					'image:path,imageable_id,imageable_type',
					// 'product:id,slug',
					// 'product.image:path,imageable_id,imageable_type'
				])
				// ->groupBy('product_id')
				->get();
		}

		if (!empty($deals['featured'])) {
			$items['featured'] = Inventory::available()
				->whereIn('id', $deals['featured'])
				// ->withCount([
				// 	'feedbacks as ratings' => function ($q2) {
				// 		$q2->select(DB::raw('avg(rating)'));
				// 	}
				// ])
				->with([
					'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
					'image:path,imageable_id,imageable_type',
					// 'product:id,slug',
					// 'product.image:path,imageable_id,imageable_type'
				])
				// ->groupBy('product_id')
				->get();
		}

		return array_merge($deals, $items);
	}
}
