<?php

namespace Comparon\SchedulingBundle\Command;

use Comparon\SchedulingBundle\Helper\TaskProcessorHelper;
use Comparon\SchedulingBundle\Model\TaskInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MegacronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('Megacron')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getApplication()->all() as $command) {
            if ($command instanceof TaskInterface) {
                $configs = $command->getTaskConfigurations();
                foreach ($configs as $config) {
                    (new TaskProcessorHelper($this->getBinDirPath(), $command, $config))->process();
                }
            }
        }
    }

    /**
     * @return string
     */
    private function getBinDirPath()
    {
        return $this->getContainer()->get('kernel')->getRootDir()
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR  . 'bin'
            . DIRECTORY_SEPARATOR
        ;
    }
}
