<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:run-sync',
    description: 'Run sync products'
)]
class RunSyncCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = 'php ' . __DIR__ . '/../../bin/console app:sync';

        $process  = Process::fromShellCommandline("$path");
        $process->start();

        $process->waitUntil(function ($type, $output)  {
            return $output !== 'Started...';
        });

        return Command::SUCCESS;
    }
}
