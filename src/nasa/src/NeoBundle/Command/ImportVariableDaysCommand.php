<?php

namespace NeoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use NeoBundle\Service\Importer;
use NeoBundle\Exception\NeoImportStructureInvalidException;

class ImportVariableDaysCommand extends ContainerAwareCommand
{
    const ARGUMENT_TOTAL_DAYS = 'total-days';

    protected function configure()
    {
        $this
            ->setName('neo:import:variableDays')
            ->setDescription('Imports variable days form nasa api')
            ->addArgument(self::ARGUMENT_TOTAL_DAYS, InputArgument::REQUIRED, 'Total days to fetch in the past')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $totalDays = $input->getArgument(self::ARGUMENT_TOTAL_DAYS);

        $output->writeln("<info>Import variable days from nasa api...</info>");
        $output->writeln("");

        /** @var NeoBundle\Service\Importer $neoImporter */
        $neoImporter = $this->getContainer()->get('neo.import');

        try {
            $end = new \DateTime();
            $start = new \DateTime();
            $start->sub(new \DateInterval('P' . 1 . 'D'));

            for ($i = 0; $i < $totalDays; $i++) {
                $neoImporter->import($start, $end);
                $start->sub(new \DateInterval('P' . 2 . 'D'));
                $end->sub(new \DateInterval('P' . 2 . 'D'));
            }

            $output->writeln("<info>Import complete!</info>");
        } catch (NeoImportStructureInvalidException $e) {
            $output->writeln("<error>Import failed: structure invalid!</error>");
        } catch (\Buzz\Exception\RequestException $e) {
            $output->writeln("<error>Import failed: invalid url!</error>");
        }
    }
}