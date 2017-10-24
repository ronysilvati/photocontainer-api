<?php

namespace PhotoContainer\PhotoContainer\Application\Shell;

use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Verify extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var
     */
    private $hints = [];

    public function __construct($name = null, ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('tools:verify')
            ->setDescription('Verificar as configurações da aplicação.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $errors = [];
        if (!is_file(ROOT_DIR.'/public/.env')) {
            $this->errors['configuration']['error'][] = 'O arquivo '.ROOT_DIR.'/public/.env não existe.';
        }

        $dotenv = new Dotenv(ROOT_DIR.'/public');
        $config = $dotenv->overload();

        if (!empty($config)) {
            $this->verifyParameters($config);
        }

        $this->verifyDirectories();
        $this->verifyProcess();
        $this->verifyDatabase();

        if (empty($this->errors)) {
            $output->writeln('<info>Configuração correta!</info>');
        }

        foreach ($this->errors as $errors) {
            foreach ($errors['hint'] as $hint) {
                $output->writeln('<comment>'.$hint.'</comment>');
            }
            $output->writeln('');

            foreach ($errors['error'] as $error) {
                $output->writeln('<error>'.$error.'</error>');
            }
        }

        $output->writeln('');
        foreach ($this->hints as $hint) {
            $output->write("<comment>$hint</comment>");
            $output->writeln('');
        }
    }

    public function verifyDatabase()
    {
        try {
            $dsn = 'mysql:host='.getenv('MYSQL_HOST');
            $pdo = new \PDO($dsn, getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'));

            $databases = $pdo->prepare("show databases;");
            $databases->execute();
            $databases = $databases->fetchAll();

            $exists = array_filter($databases, function ($item) {
                return $item['Database'] === getenv('MYSQL_DATABASE');
            });

            if (empty($exists)) {
                $this->errors['database']['error'][] = 'O banco de dados "'.getenv('MYSQL_DATABASE').'"" não existe. A migração de dados foi feita?';
            }

            $tables = $pdo->prepare(
                "SELECT count(*) as total FROM information_schema.tables where table_schema = '".getenv('MYSQL_DATABASE')."'"
            );
            $tables->execute();
            $tables = $tables->fetchAll();

            if ((int) $tables[0]['total'] === 0) {
                $this->errors['database']['error'][] = 'Não foram encontradas tabelas no Banco de Dados.';
            }

            if (isset($this->errors['database']['error'])) {
                $this->errors['database']['hint'][] = "---- ERROS NA CONFIGURAÇÃO DO BANCO DE DADOS ----";
                $this->errors['database']['hint'][] = "Verifique se as configurações foram feitas corretamentes no arquivo public/.env.";
                $this->errors['database']['hint'][] = "Além disto verifique se o Banco de Dados está online e o database configurado";
                $this->errors['database']['hint'][] = "foi criado. Por fim, realize a migração utilizando os comandos do Phinx.";
                $this->errors['database']['hint'][] = "Para migrar as tabelas execute o comando: ./vendor/bin/phinx migrate";
                $this->errors['database']['hint'][] = "Para popular o banco execute o comando: ./vendor/bin/phinx seed:run";
            }
        } catch (\Exception $e) {
            $this->errors[] = 'Não foi possível conectar no banco! Erro: '.$e->getMessage();
        }
    }

    public function verifyProcess()
    {
        $process = [
            './fotocontainer queue_process:emails' => 'ps -ax | grep "./fotocontainer queue_process:emails"',
            './fotocontainer queue_process:images' => 'ps -ax | grep "./fotocontainer queue_process:images"'
        ];

        foreach ($process as $key => $process) {
            $result = shell_exec($process);
            if(count(explode("\n", $result)) <= 3) {
                $this->errors['process']['error'][] = 'Verifique se este seguinte processo está configurado na cron: '.$key;
            }
        }

        if (isset($this->errors['process']['error'])) {
            $this->errors['process']['hint'][] = "---- ERROS NA CONFIGURAÇÃO DOS PROCESSOS DA CRON ----";
            $this->errors['process']['hint'][] = "Existem alguns comandos que são utilizados para executar tarefas em backgorund, que são";
            $this->errors['process']['hint'][] = "configurados na crontab. Siga as instruções indicadas nos erros indicados.";
        }
    }

    public function verifyDirectories(): void
    {
        $requiredDir = [
            ROOT_DIR.'/var',
            ROOT_DIR.'/var/cache',
            ROOT_DIR.'/var/logs',
            ROOT_DIR.'/var/pool',
        ];

        foreach ($requiredDir as $dir) {
            if (!is_dir($dir)) {
                $this->errors['dir']['error'][] = 'O diretório '.$dir.' não existe!';
            }
        }

        if (isset($this->errors['dir']['hint'])) {
            $this->errors['dir']['hint'][] = "---- ERROS NOS DIRETÓRIOS DA APLICAÇÃO ----";
            $this->errors['dir']['hint'][] = "Verifique a existência dos diretórios, de acordo com as mensagens de erro indicadas.";
            $this->errors['dir']['hint'][] = "Para configurar automaticamente, invoque o comando: './fotocontainer tools:install'.";

        }
    }

    public function verifyParameters(array $config): void
    {
        $verifyConfig = [
            'SHARED_PATH' => 'Diretório dos arquivos enviados (galerias, perfil) para o servidor não existe.',
            'ZIP_PATH' => 'Diretório das galerias compactadas não existe.',
            'PROFILE_IMAGE_PATH' => 'Diretório das imagens de perfil não existe.',
            'SITE_DOMAIN' => 'O domínio do site deve ser configurado.',
            'API_DOMAIN' => 'O domínio desta API deve ser configurado.',
            'MYSQL_HOST' => 'IP do MySQL.',
            'MYSQL_PORT' => 'Porta do MySQL.',
            'MYSQL_DATABASE' => 'O nome do banco de dados.',
            'MYSQL_USER' => 'Usuário para acesso.',
            'MYSQL_PASSWORD' => 'Senha para acesso.',
            'TRANSPORT' => 'Tipo do transporte de envio de email aceitos: smtp e sendmail.',
            'SMTP_HOST' => 'IP do servidor SMTP.',
            'SMTP_PORT' => 'Porta do servidor SMTP.',
            'PHOTOCONTAINER_EMAIL' => 'Os emails da aplicação vão possuir este valor no From.',
            'PHOTOCONTAINER_EMAIL_NAME' => 'Os emails da aplicação vão possuir este valor no From.',
            'HEAD_EXPIRES' => 'Expiração da cache HTTP.',
            'ADMIN_EMAIL' => 'Email do admin.',
            'ADMIN_EMAIL_NAME' => 'Email do admin.',
            'MAX_USER_SLOTS' => 'Máximo de cadastros aceitos pela aplicação.',
            'ENVIRONMENT' => 'Ambiente da aplicação, aceitos: prod e dev.',
        ];

        foreach ($verifyConfig as $key => $value) {
            if (empty(getenv($key))) {
                $this->errors['configuration']['error'][] = 'Chave configurada incorretamente: '.$key.'. '.$value;
            }
        }

        if (isset($this->errors['configuration']['error'])) {
            $this->errors['configuration']['hint'][] = "---- ERROS NA CONFIGURAÇÃO BÁSICA DA APLICAÇÃO ----";
            $this->errors['configuration']['hint'][] = "Verifique suas configurações em public/.env. Deve existir no diretório e deve estar";
            $this->errors['configuration']['hint'][] = "corretamente configurado, de acordo com as orientações descritas no arquivo .env.SAMPLE.";
        }
    }
}