<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Domain;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Result;

class octaveRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'octave:run {project_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the passed-in Project\'s answers throught the Octave neural network and store the 5 domain results in the Results table (unless that Project is marked as \'training\')';

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
        $project = Project::where('id', $this->argument('project_id'))->first();

        if ($project->training) {
            $this->error('Cannot run: Project is marked as part of the training set!');

            return Command::FAILURE;
        } // else 

        // Compile all questions and answers into binary CSV string for Octave
        $questions = Question::with(['questionAnswers', 'questionAnswers.projects'])->orderBy('title')->get();
        $csvstring = '';
        $csvarray = [];
        $rowcount = $questions->count(); // #152; - no longer hard coded, but means training must be done after changing size of answer pool.
        foreach ($questions as $question) {
            $rowcount--;
            $colcount = 20; // hard coded
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
            $csvrow = rtrim($csvrow, ","); // remove final comma
            if ($rowcount >= 0) $csvarray[] = $csvrow;
        }

        // pad out any remaining rows with zeroes (not really used unless there's a fixed hard-coded rowcount)
        $csvrow = "0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,"; // hard coded 20
        for ($i = $rowcount; $i > 0; $i--) {
            $csvstring .= $csvrow;
            $csvrow = rtrim($csvrow, ","); // remove final comma
            $csvarray[] = $csvrow;
        }
        
        // Octave code:

        chdir(storage_path() . '/app/octave');
        // Make sure X01.csv and impact.csv have writable persmissions

        // Save input as X01.csv
        $handle = fopen('X01.csv', "w");
        foreach ($csvarray as $row) {
            fwrite($handle, $row . "\n");
        }
        fclose($handle);

        // Run Octave
        exec('octave-cli mics.m');

        // Read the results from Octave
        $line = trim(fgets(fopen('impact.csv', 'r')));

        $results = explode(',', $line);

        // round results to integers (already in 0-42 range)
        foreach ($results as &$result) {
            $result = round($result);
        }
        unset($result);

        // Assuming hard coded order of results, array of 5 integers between 0-42
        // In order Society,    Governance, Economy,    Environment,    Science
        // which is 6,          4,          2,          3,              5       by Domain->id
        // should get from database domain.order?
        $totalscore = 0;

        Result::updateOrCreate(
            ['domain_id' => 6,'project_id' => $project->id],
            ['score' => $results[0]]
        );
        $totalscore += $results[0];
        Result::updateOrCreate(
            ['domain_id' => 4, 'project_id' => $project->id],
            ['score' => $results[1]]
        );
        $totalscore += $results[1];
        Result::updateOrCreate(
            ['domain_id' => 2, 'project_id' => $project->id],
            ['score' => $results[2]]
        );
        $totalscore += $results[2];
        Result::updateOrCreate(
            ['domain_id' => 3, 'project_id' => $project->id],
            ['score' => $results[3]]
        );
        $totalscore += $results[3];
        Result::updateOrCreate(
            ['domain_id' => 5, 'project_id' => $project->id],
            ['score' => $results[4]]
        );
        $totalscore += $results[4];

        return Command::SUCCESS;
    }
}
