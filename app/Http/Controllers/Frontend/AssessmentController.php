<?php

namespace App\Http\Controllers\Frontend;
use App\Models\Project;
use App\Models\Domain;
use App\Models\Question;
use App\Models\Answer;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AssessmentController
{

    public function domainmap($project_slug)
    {
        $project = Project::where('slug', $project_slug)->first();

        $domains = Domain::withCount(['domainQuestions'])->get();

        // Get the number of questions answered for each domain
        foreach ($domains as $domain) {
            $domain['questionsanswered'] = $project->projectsAnswers()->whereRelation('question', 'domain_id', '=', $domain->id)->groupBy('question_id')->pluck('question_id', 'question_id')->count();
            if (0 < $domain->domain_questions_count) {
                $domain['percentanswered'] = round(100 * $domain->questionsanswered / $domain->domain_questions_count);
            } else {
                $domain['percentanswered'] = 0;
            }
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

        return view('frontend.domainmap',  compact('project', 'domains'));
    }


    public function show($project_slug, $domain_slug)
    {
        $project = Project::where('slug', $project_slug)->with(['projectsAnswers', 'user', 'projectsAnswers.answerBlocklists.questions'])->first();
        
        $domains = Domain::withCount(['domainQuestions'])->get();

        // Get the number of questions answered for each domain
        foreach ($domains as $domain) {
            $domain['questionsanswered'] = $project->projectsAnswers()->whereRelation('question', 'domain_id', '=', $domain->id)->groupBy('question_id')->pluck('question_id', 'question_id')->count();
            if (0 < $domain->domain_questions_count) {
                $domain['percentanswered'] = round(100 * $domain->questionsanswered / $domain->domain_questions_count);
            } else {
                $domain['percentanswered'] = 0;
            }
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

        $currentdomain = Domain::where('slug', $domain_slug)->with(['domainQuestions.questionAnswers'])->first();
        $questionsanswered = $project->projectsAnswers()->whereRelation('question', 'domain_id', '=', $currentdomain->id)->groupBy('question_id')->pluck('question_id', 'question_id')->count();

        // get blocked questions
        $blocklist = [];
        foreach ($project->projectsAnswers as $answer) { 
            foreach ($answer->answerBlocklists as $blocklist) { 
                foreach ($blocklist->questions as $question ) { 
                    //$blocklist[] = $question->id;
                }  
            } 
        }

        if ($project && $project->user->id == Auth::user()->id) {
            return view('frontend.assessment', compact('project', 'currentdomain', 'questionsanswered', 'domains', 'blocklist'));
        } else {
            abort(403, "Sorry, this Project doesn't belong to you.");
        }
    }

    public function answer(Request $request)
    {
        error_reporting(E_ALL);

        $input = $request->all();

        $project = Project::where('id', $input['projectid'])->with(['projectsAnswers', 'user'])->first();
        $possibleanswers = Question::where('id', $input['questionid'])->with('questionAnswers')->first()->questionAnswers()->pluck('id');
        $domain_id = Question::where('id', $input['questionid'])->with('domain')->first()->domain()->pluck('id')->toArray()[0];

        // make sure Project is owned by currently logged in user
        if ($project && $project->user->id == Auth::user()->id) {

            // Get rid of the old values
            $project->projectsAnswers()->detach($possibleanswers);
    
            // Add in the submitted ones
            if (array_key_exists('answerarray', $input) && count($input['answerarray'])>0) {
                $project->projectsAnswers()->attach($input['answerarray']);
            }

            $questionsanswered = $project->projectsAnswers()->whereRelation('question', 'domain_id', '=', $domain_id)->groupBy('question_id')->pluck('question_id', 'question_id')->count();

            return response()->json(['questionsanswered' => $questionsanswered]);

        } else {
        
            return response()->json(['error' => "Project doesn't belong to you."],403);
        
        }

    }
}