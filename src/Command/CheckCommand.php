<?php

declare(strict_types=1);

namespace Shared\Deletion\Command;

use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    #[Override]
    protected function configure(): void
    {
        $this->setName('deletion:check');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
