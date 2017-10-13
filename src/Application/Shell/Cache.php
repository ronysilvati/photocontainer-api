<?php

namespace PhotoContainer\PhotoContainer\Application\Shell;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Cache extends Command
{
    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $dir;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('cache:purge')
            ->setDescription('Limpar a cache do Nginx.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        shell_exec('curl -X GET '.getenv('API_DOMAIN').'/purge');
    }
}