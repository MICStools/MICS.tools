<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        return Command::SUCCESS;
    }
}
