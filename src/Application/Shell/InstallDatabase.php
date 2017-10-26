<?php

namespace PhotoContainer\PhotoContainer\Application\Shell;

use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class InstallDatabase extends Command
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pwd;

    /**
     * @var string
     */
    private $database;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('tools:database_install')
            ->setDescription('Configurar o Banco de Dados.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln('<info>Ferramenta de migração de Banco de dados</info>');

        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion('Deseja migrar o banco (s/n)? ', false, '/^s/i');
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $question = new Question('Qual o host do servidor MySQL (IP ou hostname)? ');
        $this->host = $helper->ask($input, $output, $question);

        $question = new Question('Qual o usuário que possui permissão para criar banco/tabelas? ');
        $this->user = $helper->ask($input, $output, $question);

        $question = new Question('Qual a senha desse usuário? ');
        $this->pwd = $helper->ask($input, $output, $question);

        $question = new Question('Informe o nome ao Banco de Dados a ser criado: ');
        $this->database = $helper->ask($input, $output, $question);

        if (!$this->createDatabase()) {
            return;
        }
    }

    private function createDatabase(): bool
    {
        try {
            $dsn = 'mysql:host='.$this->host;
            $pdo = new \PDO($dsn, $this->user, $this->pwd);

            $databases = $pdo->prepare("show databases;");
            $databases->execute();
            $databases = $databases->fetchAll();

            $exists = array_filter($databases, function ($item) {
                return $item['Database'] === $this->database;
            });

            if (!empty($exists)) {
                $this->output->writeln('<error>Já existe um banco de dados com este nome. Abortando!</error>');
                return false;
            }

            $pdo->exec("CREATE DATABASE ".$this->database.";");

            return true;
        } catch (\Exception $e) {
            $this->output->writeln('<error>Não foi possível completar a operação!</error>');
            $this->output->writeln('<error>'.$e->getMessage().'</error>');

            return false;
        }
    }
}