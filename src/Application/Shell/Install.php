<?php

namespace PhotoContainer\PhotoContainer\Application\Shell;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Command
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
    protected function configure(): void
    {
        $this->fetchConfiguration();

        $this
            ->setName('tools:install')
            ->setDescription('Verificar e instalar a aplicação.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->files as $file) {
            if (!file_exists($file['file'])) {
                $output->writeln($file['msg_not_exists']);
                shell_exec($file['command']);
            } else {
                $output->writeln($file['msg_exists']);
            }
        }

        foreach ($this->dir as $dir) {
            if (!file_exists($dir)) {
                $output->writeln('Gerando diretório: '.$dir);
                mkdir($dir, 0777);
            } else {
                $output->writeln('Diretório existente: '.$dir);
            }
        }
    }

    public function fetchConfiguration()
    {
        $this->files = [
            'composer_file' => [
                'file' => ROOT_DIR.'/composer.phar',
                'msg_exists' => '<info>Composer existente.</info>',
                'msg_not_exists' => '<info>Instalando composer...</info>',
                'command' => 'cd '.ROOT_DIR.'; wget https://getcomposer.org/composer.phar',
            ],
            'composer_lock' => [
                'file' => ROOT_DIR.'/composer.lock',
                'msg_exists' => '<info>Dependências existentes.</info>',
                'msg_not_exists' => '<info>Inicializando instalação...</info>',
                'callback' => 'cd '.ROOT_DIR.'; php composer.phar install',
            ],
            'dot_env' => [
                'file' => ROOT_DIR.'/public/.env',
                'msg_exists' => '<info>Arquivo .env existente.</info>',
                'msg_not_exists' =>
                    '<info>Movendo env.SAMPLE para "public/.env". Este arquivo DEVE ser configurado manualmente</info>',
                'callback' => 'cd '.ROOT_DIR.'/public; mv public/env.SAMPLE .env',
            ],
        ];

        $this->dir = [
            ROOT_DIR.'/var',
            ROOT_DIR.'/var/cache',
            ROOT_DIR.'/var/logs',
            ROOT_DIR.'/var/pool',
        ];
    }
}