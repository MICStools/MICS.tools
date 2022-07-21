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

        // Process the Octave Results for display - move this to the database!
        foreach ($project->projectResults as $result) {
            if (0 < $totalscore) {
                $result['percentage'] = $result->score / $totalscore;
            } else {
                $result['percentage'] = 0;
            }
            switch ($result->domain->slug) {
                case "environment":
                    $result->domain['colour'] = '#3ca891';
                    break;
                case "science":
                    $result->domain['colour'] = '#223242';
                    break;
                case "economy":
                    $result->domain['colour'] = '#2d7dc6';
                    break;
                case "governance":
                    $result->domain['colour'] = '#e15477';
                    break;
                case "society":
                    $result->domain['colour'] = '#eeba30';
                    break;
                default:
                    $result->domain['colour'] = '#3ca891';
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

        // Compile all questions and answers into binary CSV string for Octave
        $questions = Question::with(['questionAnswers', 'questionAnswers.projects'])->orderBy('title')->get();
        $csvstring = '';
        $csvarray = [];
        $rowcount = $questions->count(); #152;
        foreach ($questions as $question) {
            $rowcount--;
            $colcount = 20;
            $csvrow = '';
            foreach ($question->questionAnswers as $answer) {
                $colcount--;
                if ($answer->projects->contains($project->id)) {
                    $csvrow .= '1,';
                } else {
                    $csvrow .= '0,';
                }
            }
            // pad out columns with zeroes
            for ($i = $colcount; $i > 0; $i--) {
                $csvrow .= '0,';
            }
            $csvstring .= $csvrow;
            // truncate to 152 rows for now, as Octave script is fragile
            $csvrow = rtrim($csvrow, ","); // remove final comma
            if ($rowcount >= 0) $csvarray[] = $csvrow;
        }

        // pad out any remaining rows with zeroes (not used unless there's a fixed hard-coded rowcount)
        $csvrow = "0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,";
        for ($i = $rowcount; $i > 0; $i--) {
            $csvstring .= $csvrow;
            $csvrow = rtrim($csvrow, ","); // remove final comma
            $csvarray[] = $csvrow;
        }
        
        //ddd($csvstring);

        // Get the Octave Results from AWS API
        //$response = Http::get('http://dev.mics.tools/octave.php?X1=' . $csvstring);
        
        //$response = Http::asForm()->post('http://dev.mics.tools/post.php', [
        //    'X1' => $csvarray,
        //    'secret' => '3E5RSKic2WzoDhR2po7G',
        //]);

        $response = Http::asForm()->post('http://dev.mics.tools/octave.php', $csvarray);
        //ddd($response->json());

        if (null == $response) {
            $response = [0,0,0,0,0];
        } 

        // Assuming hard coded order of results, array of 5 integers between 0-42
        // In order Society,    Governance, Economy,    Environment,    Science
        // which is 6,          4,          2,          3,              5       by Domain->id
        $totalscore = 0;

        Result::updateOrCreate(
            ['domain_id' => 6,'project_id' => $project->id],
            ['score' => $response->json()[0]]
        );
        $totalscore += $response->json()[0];
        Result::updateOrCreate(
            ['domain_id' => 4, 'project_id' => $project->id],
            ['score' => $response->json()[1]]
        );
        $totalscore += $response->json()[1];
        Result::updateOrCreate(
            ['domain_id' => 2, 'project_id' => $project->id],
            ['score' => $response->json()[2]]
        );
        $totalscore += $response->json()[2];
        Result::updateOrCreate(
            ['domain_id' => 3, 'project_id' => $project->id],
            ['score' => $response->json()[3]]
        );
        $totalscore += $response->json()[3];
        Result::updateOrCreate(
            ['domain_id' => 5, 'project_id' => $project->id],
            ['score' => $response->json()[4]]
        );
        $totalscore += $response->json()[4];

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
        //$totalscore = $project->projectResults->sum('score');

        // Process the Octave Results for display - move this to the database!
        foreach ($project->projectResults as $result) {
            if (0 < $totalscore) {
                $result['percentage'] = $result->score / $totalscore;
            } else {
                $result['percentage'] = 0;
            }
            switch ($result->domain->slug) {
                case "environment":
                    $result->domain['colour'] = '#3ca891';
                    break;
                case "science":
                    $result->domain['colour'] = '#223242';
                    break;
                case "economy":
                    $result->domain['colour'] = '#2d7dc6';
                    break;
                case "governance":
                    $result->domain['colour'] = '#e15477';
                    break;
                case "society":
                    $result->domain['colour'] = '#eeba30';
                    break;
                default:
                    $result->domain['colour'] = '#3ca891';
            }
            
        }

        // Calculate the average for in the middle of the pie chart
        $average = round(42 * ($totalscore / (42*5)));

        return view('frontend.projects.summary', compact('project', 'domainRecommendations', 'domainIndicators', 'organisersstring', 'average', 'totalprogress'));
    }

}
