<?php

namespace App\Command;

use App\Entity\Foo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test:orm')]
final class TestOrmCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $t1 = microtime(true);

        for ($i = 0; $i < 1500; $i++) {
            for ($j = 0; $j < 1000; $j++) {
                $this->entityManager->getReference(Foo::class, $j + 1);
            }

            $this->entityManager->clear();
        }

        $t2 = microtime(true);

        $output->writeln(sprintf('Time: %0.6f', $t2 - $t1));

        return Command::SUCCESS;
    }
}
