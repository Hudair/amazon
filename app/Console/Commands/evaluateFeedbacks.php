<?php

namespace App\Console\Commands;

use App\Services\ClassFinder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class evaluateFeedbacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incevio:evaluate-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Evaluate ratings for models like Inventories, shops';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Select only classes that use Feedbackable trait
        $models = array_filter(
            ClassFinder::getClassesInNamespace('App\Models'),
            function ($className) {
                $traits = class_uses($className);

                return isset($traits[\App\Common\Feedbackable::class]);
            }
        );

        foreach ($models as $model) {
            $now = Carbon::Now();
            // Log::info('Re-evaluating ' . $model . ' at: ' . $now);

            $model::withCount([
                'feedbacks',
                'feedbacks as tempAvgRatings' => function ($q2) {
                    $q2->select(DB::raw('avg(rating)'));
                },
            ])->chunkById(5, function ($items) use ($now, $model) {
                DB::beginTransaction();

                foreach ($items as $item) {
                    if (!$item->tempAvgRatings) continue;

                    DB::table('avg_feedback')->updateOrInsert(
                        ['feedbackable_id' => $item->id, 'feedbackable_type' => $model],
                        [
                            'rating' => $item->tempAvgRatings,
                            'count' => $item->feedbacks_count,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }

                DB::commit();
            });

            // Log::info('Re-evaluation of ' . $model . '  is done! Took: ' . Carbon::Now()->diff($now)->format('%H:%I:%S'));
        }
    }
}
