<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException; 

class GitPullJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $process = new Process(['git',  'pull'], '/home/ldt/Projects/BE_Tutor_Events'); 
        // // $process->setWorkingDirectory('/home/ldt/Projects/BE_Tutor_Events');

        // $process->run(); // executes after the command finishes 
        
        // if (!$process->isSuccessful()) { 
        //     throw new ProcessFailedException($process); 
        // } 
        
        // if (!$process->isSuccessful()) { 
        //     throw new ProcessFailedException($process); 
        // } 

        // // $process->setCommandLine('sudo reboot -f'); //Set a new Command to the current process
        // // $process->run();                            //Run this process again

        // // if (!$process->isSuccessful()) {            //Executes after the command finishes
        // //     throw new ProcessFailedException($process);
        // // }

        // echo $process->getOutput();
        // die;


        $cmd = 'git pull';
        $cwd = base_path();
        $process = Process::fromShellCommandline($cmd, $cwd);

        $processOutput = '';

        $captureOutput = function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        };

        $process->setTimeout(null)
            ->run($captureOutput);

            echo ($processOutput);
    }
}
