<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Cache;
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

        // Run the Octave neural net, which saves the Results to the Results table
        Artisan::call('octave:run', ['project_id' => $project->id]);

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
        
        // Now get the rest of the data 

        // Prepare data for Google GeoChart to display the map on the Front End in JS:

        $caf = "['DZ'],
                ['AO'],
                ['BJ'],
                ['BW'],
                ['BF'],
                ['BI'],
                ['CM'],
                ['CV'],
                ['CF'],
                ['TD'],
                ['KM'],
                ['CD'],
                ['CG'],
                ['CI'],
                ['DJ'],
                ['EG'],
                ['GQ'],
                ['ER'],
                ['ET'],
                ['GA'],
                ['GM'],
                ['GH'],
                ['GN'],
                ['GW'],
                ['KE'],
                ['LS'],
                ['LR'],
                ['LY'],
                ['MG'],
                ['MW'],
                ['ML'],
                ['MR'],
                ['MU'],
                ['YT'],
                ['MA'],
                ['MZ'],
                ['NA'],
                ['NE'],
                ['NG'],
                ['RE'],
                ['RW'],
                ['SH'],
                ['ST'],
                ['SN'],
                ['SC'],
                ['SL'],
                ['SO'],
                ['ZA'],
                ['SS'],
                ['SD'],
                ['SZ'],
                ['TZ'],
                ['TG'],
                ['TN'],
                ['UG'],
                ['EH'],
                ['ZM'],
                ['ZW'],";
        
        $cna = "['AI'],
                ['AG'],
                ['AW'],
                ['BS'],
                ['BB'],
                ['BZ'],
                ['BM'],
                ['BQ'],
                ['VG'],
                ['CA'],
                ['KY'],
                ['CR'],
                ['CU'],
                ['CW'],
                ['DM'],
                ['DO'],
                ['SV'],
                ['GL'],
                ['GD'],
                ['GP'],
                ['GT'],
                ['HT'],
                ['HN'],
                ['JM'],
                ['MQ'],
                ['MX'],
                ['MS'],
                ['NI'],
                ['PA'],
                ['PR'],
                ['BL'],
                ['KN'],
                ['LC'],
                ['MF'],
                ['PM'],
                ['VC'],
                ['SX'],
                ['TT'],
                ['TC'],
                ['US'],
                ['VI'],";

        $coc = "['AS'],
                ['AU'],
                ['CK'],
                ['FJ'],
                ['PF'],
                ['GU'],
                ['KI'],
                ['MH'],
                ['FM'],
                ['NR'],
                ['NC'],
                ['NZ'],
                ['NU'],
                ['NF'],
                ['MP'],
                ['PW'],
                ['PG'],
                ['PN'],
                ['WS'],
                ['SB'],
                ['TK'],
                ['TO'],
                ['TV'],
                ['UM'],
                ['VU'],
                ['WF'],";

        $can = "['AQ'],
                ['BV'],
                ['TF'],
                ['HM'],
                ['GS'],";

        $cas = "['AF'],
                ['AM'],
                ['AZ'],
                ['BH'],
                ['BD'],
                ['BT'],
                ['IO'],
                ['BN'],
                ['KH'],
                ['CN'],
                ['CX'],
                ['CC'],
                ['CY'],
                ['GE'],
                ['HK'],
                ['IN'],
                ['ID'],
                ['IR'],
                ['IQ'],
                ['IL'],
                ['JP'],
                ['JO'],
                ['KZ'],
                ['KP'],
                ['KR'],
                ['KW'],
                ['KG'],
                ['LA'],
                ['LB'],
                ['MO'],
                ['MY'],
                ['MV'],
                ['MN'],
                ['MM'],
                ['NP'],
                ['OM'],
                ['PK'],
                ['PS'],
                ['PH'],
                ['QA'],
                ['SA'],
                ['SG'],
                ['LK'],
                ['SY'],
                ['TW'],
                ['TJ'],
                ['TH'],
                ['TL'],
                ['TR'],
                ['TM'],
                ['AE'],
                ['UZ'],
                ['VN'],
                ['YE'],";

        $ceu = "['AX'],
                ['AL'],
                ['AD'],
                ['AT'],
                ['BY'],
                ['BE'],
                ['BA'],
                ['BG'],
                ['HR'],
                ['CZ'],
                ['DK'],
                ['EE'],
                ['FO'],
                ['FI'],
                ['FR'],
                ['DE'],
                ['GI'],
                ['GR'],
                ['GG'],
                ['VA'],
                ['HU'],
                ['IS'],
                ['IE'],
                ['IM'],
                ['IT'],
                ['JE'],
                ['LV'],
                ['LI'],
                ['LT'],
                ['LU'],
                ['MT'],
                ['MD'],
                ['MC'],
                ['ME'],
                ['NL'],
                ['MK'],
                ['NO'],
                ['PL'],
                ['PT'],
                ['RO'],
                ['RU'],
                ['SM'],
                ['RS'],
                ['SK'],
                ['SI'],
                ['ES'],
                ['SJ'],
                ['SE'],
                ['CH'],
                ['UA'],
                ['GB'],";

        $csa = "['AR'],
                ['BO'],
                ['BR'],
                ['CL'],
                ['CO'],
                ['EC'],
                ['FK'],
                ['GF'],
                ['GY'],
                ['PY'],
                ['PE'],
                ['SR'],
                ['UY'],
                ['VE'],";

        $ww  = $caf . $cna . $coc . $can . $cas . $ceu . $csa;

        // 1 Organisers
        $organisers = $project->organisers()->pluck('short_code')->toArray();
        $organisersstring = "['country'],";
        foreach ($organisers as $organiser) {
            // for Continents and Worldwide, include all relevant country codes
            switch ($organiser) {
                case "caf":
                    $organisersstring .= $caf;
                    break;

                case "cna":
                    $organisersstring .= $cna;
                    break;

                case "coc":
                    $organisersstring .= $coc;
                    break;

                case "can":
                    $organisersstring .= $can;
                    break;

                case "cas":
                    $organisersstring .= $cas;
                    break;

                case "ceu":
                    $organisersstring .= $ceu;
                    break;

                case "csa":
                    $organisersstring .= $csa;
                    break;

                case "ww":
                    $organisersstring .= $ww;
                    break;
                // Or just the selected country code
                default:
                    $organisersstring .= '[\''.strtoupper($organiser).'\'],';
            }
            
        }

        // 2 Participants
        $participants = $project->participants()->pluck('short_code')->toArray();
        $participantsstring = "['country'],";
        foreach ($participants as $participant) {
            // for Continents and Worldwide, include all relevant country codes
            switch ($participant) {
                case "caf":
                    $participantsstring .= $caf;
                    break;

                case "cna":
                    $participantsstring .= $cna;
                    break;

                case "coc":
                    $participantsstring .= $coc;
                    break;

                case "can":
                    $participantsstring .= $can;
                    break;

                case "cas":
                    $participantsstring .= $cas;
                    break;

                case "ceu":
                    $participantsstring .= $ceu;
                    break;

                case "csa":
                    $participantsstring .= $csa;
                    break;

                case "ww":
                    $participantsstring .= $ww;
                    break;
                // Or just the selected country code
                default:
                    $participantsstring .= '[\''.strtoupper($participant).'\'],';
            }
        }

        // 3 Observers
        $observers = $project->observers()->pluck('short_code')->toArray();
        $observersstring = "['country'],";
        foreach ($observers as $observer) {
            // for Continents and Worldwide, include all relevant country codes
            switch ($observer) {
                case "caf":
                    $observersstring .= $caf;
                    break;

                case "cna":
                    $observersstring .= $cna;
                    break;

                case "coc":
                    $observersstring .= $coc;
                    break;

                case "can":
                    $observersstring .= $can;
                    break;

                case "cas":
                    $observersstring .= $cas;
                    break;

                case "ceu":
                    $observersstring .= $ceu;
                    break;

                case "csa":
                    $observersstring .= $csa;
                    break;

                case "ww":
                    $observersstring .= $ww;
                    break;
                // Or just the selected country code
                default:
                    $observersstring .= '[\''.strtoupper($observer).'\'],';
            }
        }
        
        
        // Get Recommendations that are Impact Indicators for each Domain
        $domainIndicators = Domain::with(['recommendations' => function ($query) { $query->where('indicator', 1); }])->orderBy('order')->get();

        // Calculate this projects' score for each Indicator
        foreach ($domainIndicators as $domain) {
            
                foreach ($domain['recommendations'] as $indicator) {
                    $indicator_id = $indicator['id'];
                    $score = Answer::whereRelation('projects', 'id', $project->id)->whereHas('question.recommendations', function ($query) use($indicator_id) {$query->where('id', $indicator_id); })->sum('weight');
                    $indicator['score'] = $score;
                    $indicator['average'] = Cache::get($indicator_id, 0);
                }
    
        }

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

        return view('frontend.projects.summary', compact('project', 'results', 'domainRecommendations', 'domainIndicators', 'organisersstring', 'participantsstring', 'observersstring', 'average', 'totalprogress'));
    }

}
