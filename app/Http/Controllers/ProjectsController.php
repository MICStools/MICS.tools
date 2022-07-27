<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Country;
use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use App\Models\Domain;
use App\Models\Recommendation;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Result;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;


class ProjectsController extends Controller
{

    public function show($project_slug)
    {

        //$project->load('user', 'organisers', 'created_by', 'projectResults', 'projectsAnswers')->dd();
        $project = Project::where('slug', $project_slug)->with(['user', 'organisers', 'created_by', 'projectResults', 'topics'])->first();

        if (null == $project) {
            return abort(404);
        }
        
        $domains = Domain::withCount(['domainQuestions'])->get();

        // Get the number of questions answered for each domain
        $totalprogress = 0;
        foreach ($domains as $domain) {
            $domain['questionsanswered'] = $project->projectsAnswers()->whereRelation('question', 'domain_id', '=', $domain->id)->groupBy('question_id')->pluck('question_id', 'question_id')->count();
            if (0 < $domain->domain_questions_count) {
                $domain['percentanswered'] = round(100 * $domain->questionsanswered / $domain->domain_questions_count);
            } else {
                $domain['percentanswered'] = 0;
            }
            $totalprogress += $domain['percentanswered'];
            // getting ridiculous - put this in the DB!
            switch ($domain->slug) {
                case "environment":
                    $domain['colour'] = '#3ca891';
                    break;
                case "science":
                    $domain['colour'] = '#223242';
                    break;
                case "economy":
                    $domain['colour'] = '#2d7dc6';
                    break;
                case "governance":
                    $domain['colour'] = '#e15477';
                    break;
                case "society":
                    $domain['colour'] = '#eeba30';
                    break;
                default:
                    $domain['colour'] = '#666666';
            }
        }
        $totalprogress = round($totalprogress / 6); # Find the average percentage from the 6 domains

        // work out the percentage for the total donut chart
        $totalscore = $project->projectResults->sum('score');

        // Process the Octave Results for display
        foreach ($project->projectResults as $result) {
            if (0 < $totalscore) {
                $result['percentage'] = $result->score / $totalscore;
            } else {
                $result['percentage'] = 0;
            }
            
        }

        // Calculate the average for in the middle of the total donut chart
        $average = round(42 * ($totalscore / (42*5)));

        return view('frontend.projects.show', compact('project', 'domains', 'average', 'totalprogress'));
    }

    public function summary($project_slug)
    {
        $project = Project::where('slug', $project_slug)->with(['user', 'projectResults.domain' => function ($q){$q->orderBy('order');}, 'topics'])->first();
        if (null == $project) {
            return abort(404);
        }

        $domains = Domain::withCount(['domainQuestions'])->get();

        // Get the number of questions answered for each domain
        $totalprogress = 0;
        foreach ($domains as $domain) {
            $domain['questionsanswered'] = $project->projectsAnswers()->whereRelation('question', 'domain_id', '=', $domain->id)->groupBy('question_id')->pluck('question_id', 'question_id')->count();
            if (0 < $domain->domain_questions_count) {
                $domain['percentanswered'] = round(100 * $domain->questionsanswered / $domain->domain_questions_count);
            } else {
                $domain['percentanswered'] = 0;
            }
            $totalprogress += $domain['percentanswered'];
        }
        $totalprogress = round($totalprogress / 6); # Find the average percentage from the 6 domains

        // Run the Octave neural net, which saves the Results to the Results table
        Artisan::call('octave:run', ['project_id' => $project->id]);
        
        // Now get the rest of the data 

        // Prepare data for Google GeoChart to display the map on the Front End in JS
        $organisers = $project->organisers()->pluck('short_code')->toArray();
        $organisersstring = "['country'],";
        foreach ($organisers as $organiser) {
            $organisersstring .= '[\''.strtoupper($organiser).'\'],';
        }
        //ddd($organisersstring);
        
        // Get Recommendations that are Impact Indicators for each Domain
        $domainIndicators = Domain::with(['recommendations' => function ($query) { $query->where('indicator', 1); }])->orderBy('order')->get();

        // Calculate this projects' score for each Indicator
        foreach ($domainIndicators as $domain) {
            
                foreach ($domain['recommendations'] as $indicator) {
                    $indicator_id = $indicator['id'];
                    $score = Answer::whereRelation('projects', 'id', $project->id)->whereHas('question.recommendations', function ($query) use($indicator_id) {$query->where('id', $indicator_id); })->sum('weight');
                    $indicator['score'] = $score;
                }
            
        }

        // Get the cached averages and calculate difference (updated at midnight each day)
        // TO DO

        // Get the other Recommendations that aren't Impact Indicators for each Domain
        $domainRecommendations = Domain::with(['recommendations' => function ($query) { $query->where('indicator', 0); }])->orderBy('order')->get();

        // Calculate their score for current Project and remove from list if outside Min-Max (min exclusive, max inclusive)
        foreach ($domainRecommendations as $domain) {
            foreach ($domain['recommendations'] as $key => $recommendation) {
                $recommendation_id = $recommendation['id'];
                $score = Answer::whereRelation('projects', 'id', $project->id)->whereHas('question.recommendations', function ($query) use($recommendation_id) {$query->where('id', $recommendation_id); })->sum('weight');
                $recommendation['score'] = $score;
                if ($score <= $recommendation['minscore'] || $score > $recommendation['maxscore']) {
                    unset($domain['recommendations'][$key]);
                }
            }
        }

        // work out the percentage for the pie chart
        $totalscore = $project->projectResults->sum('score');

        // Process the Octave Results for display
        $results = Result::where('project_id', $project->id)->get();

        foreach ($results as $result) {
            if (0 < $totalscore) {
                $result['percentage'] = $result->score / $totalscore;
            } else {
                $result['percentage'] = 0;
            }
            
        }

        // Calculate the average for in the middle of the pie chart
        $average = round(42 * ($totalscore / (42*5)));

        return view('frontend.projects.summary', compact('project', 'results', 'domainRecommendations', 'domainIndicators', 'organisersstring', 'average', 'totalprogress'));
    }

}
