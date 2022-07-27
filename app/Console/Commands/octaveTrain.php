<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Domain;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Result;

class octaveTrain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'octave:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train the neural network on the Results and Answers for Projects marked as \'training\' projects.';

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
        // Get the first 9 training projects
        $trainingProjects = Project::where('training', 1)->with(['projectResults' , 'projectResults.domain'])->take(9)->get()->sortBy('projectResults.domain.order');

        // Get all the answers, sorted by question title
        $questions = Question::with(['questionAnswers', 'questionAnswers.projects'])->orderBy('title')->get();

        chdir(storage_path() . '/app/octave');
        // Make sure X01.csv to X09.csv have writable persmissions

        // Process them
        $projectsleft = 9;
        $yfilearray = [];
        foreach ($trainingProjects as $project) {
            $projectsleft--;
            // Compile all questions and answers into binary CSV string for Octave
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

            /* $this->line((9-$projectsleft) . ': ');
            $this->line($csvarray);
            $this->newLine(); */

            // Save input as X01.csv-X09.csv
            $handle = fopen('X0' . (9-$projectsleft) . '.csv', "w");
            foreach ($csvarray as $row) {
                fwrite($handle, $row . "\n");
            }
            fclose($handle);

            // Get saved Results for y file:
          
            // Assuming hard coded order of results, array of 5 integers between 0-42
            // In order Society,    Governance, Economy,    Environment,    Science
            // which is 6,          4,          2,          3,              5       by Domain->id
            // which is 1,          2,          3,          4,              5       by Domain->order
            // We've done sortBy on the $trainingProjects collection to match this
            $yrow = '';
            foreach ($project->projectResults as $result) {
                $yrow .= $result->score . ',';
            }
            $yrow = rtrim($yrow, ","); // remove final comma
            $yfilearray[] = $yrow;
        }

        $this->line($yfilearray);
        $this->newLine();

        // write out Y file
        // Make sure Y.csv has writable persmissions
        $handle = fopen('Y.csv', "w");
        foreach ($yfilearray as $row) {
            fwrite($handle, $row . "\n");
        }
        fclose($handle);

        // Run Octave Training script
        $output=null;
        $retval=null;
        exec('octave trainingMics.m', $output, $retval);

        if (0 == $retval) {
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }
    }
}
