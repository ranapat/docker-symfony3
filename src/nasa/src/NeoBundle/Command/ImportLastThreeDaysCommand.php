<?php

namespace NeoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use NeoBundle\Service\Importer;
use NeoBundle\Exception\NeoImportStructureInvalidException;

class ImportLastThreeDaysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('neo:import:lastThreeDays')
            ->setDescription('Imports last 3 days form nasa api')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Import last 3 days from nasa api...</info>");
        $output->writeln("");

        /** @var NeoBundle\Service\Importer $neoImporter */
        $neoImporter = $this->getContainer()->get('neo.import');

        try {
            $neoImporter->import();

            $output->writeln("<info>Import complete!</info>");
        } catch (NeoImportStructureInvalidException $e) {
            $output->writeln("<error>Import failed: structure invalid!</error>");
        } catch (\Buzz\Exception\RequestException $e) {
            $output->writeln("<error>Import failed: invalid url!</error>");
        }
    }
}
