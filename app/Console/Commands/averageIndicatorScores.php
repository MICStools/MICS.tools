<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Project;
use App\Models\Domain;
use App\Models\Recommendation;
use App\Models\Answer;
use App\Models\Question;

class averageIndicatorScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'averageIndicatorScore:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the cache of average Indicator Scores (Indicators are special Recommendations)';

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
     * @return int
     */
    public function handle()
    {

        // For each indicator, go through every project and calculate their score, sum, and divide by number if projects, then save in the cache
        
        // Get Recommendations that are Impact Indicators for each Domain
        $domainIndicators = Domain::with(['recommendations' => function ($query) { $query->where('indicator', 1); }])->orderBy('order')->get();

        // Get all project ID's
        $projects = Project::all()->pluck('id');

        // Calculate this projects' score for each Indicator
        foreach ($domainIndicators as $domain) {
            
            foreach ($domain['recommendations'] as $indicator) {
                $indicator_id = $indicator['id'];
                $totalscore = 0;

                foreach ($projects as $projectid) {
                    $score = Answer::whereRelation('projects', 'id', $projectid)->whereHas('question.recommendations', function ($query) use($indicator_id) {$query->where('id', $indicator_id); })->sum('weight');
                    $totalscore += $score;
                }

                $avgscore = (int) round($totalscore / $projects->count());

                Cache::put($indicator_id, $avgscore);
            }
            
        }

        return Command::SUCCESS;
    }
}
