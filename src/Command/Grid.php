<?php

namespace Flood\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Grid extends Command
{
    protected function configure()
    {
        $this
            ->setName('grid')
            ->setDescription('Interact with grids')
            ->addArgument(
                'task',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
                'apikey',
                'k',
                InputOption::VALUE_REQUIRED,
                'Your API key for accessing flood.io'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('apikey')) {
            $client = new Client($input->getOption('apikey'));
        }

        print_r($client->floodList());

        $output->writeln($text);
    }
}
