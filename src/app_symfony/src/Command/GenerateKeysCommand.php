<?php

namespace App\Command;

use App\Service\KeyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:generate-keys')]
class GenerateKeysCommand extends Command
{
    public function __construct(
        private KeyService $keyService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'keysNumber',
            InputArgument::OPTIONAL,
            'Number of keys to generate (default: 1000)',
            1000
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Generating random keys');
        $keysGenerated = $this->keyService->generateAndSaveKeys($input->getArgument('keysNumber'));
        $output->writeln('Keys generated: '. $keysGenerated);
        return Command::SUCCESS;
    }
}
