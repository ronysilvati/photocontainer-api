<?php

namespace PhotoContainer\PhotoContainer\Application\Shell;

use Interop\Queue\PsrContext;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftQueueSpool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Swift_SpoolTransport;

class EmailPoolConsumer extends Command
{
    /**
     * @var Swift_SpoolTransport
     */
    private $transport;

    /**
     * EmailPoolConsumer constructor.
     * @param null $name
     * @param PsrContext $psrContext
     */
    public function __construct($name = null, PsrContext $psrContext)
    {
        $this->transport = new Swift_SpoolTransport(new SwiftQueueSpool($psrContext));
        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('queue_process:emails')
            ->setDescription('Escuta e envia os emails na fila.');

        $this->addArgument(
            'timelimit',
            InputArgument::OPTIONAL,
            'Tempo (em segundos) em que o processo escuta os emails.',
            60
        );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Inicializando fila...</info>');

        $spool = $this->transport->getSpool();
        $spool->setTimeLimit($input->getArgument('timelimit'));

        if (getenv('ENVIRONMENT') === 'prod') {
            $realTransport = new \Swift_SendmailTransport('/usr/lib/sendmail -bs');
        } else {
            $realTransport = new \Swift_SmtpTransport('192.168.99.100','1025');
        }

        $output->writeln('<info>Escutando fila...</info>');
        $totalSent = $spool->flushQueue($realTransport);

        $output->writeln("<info>Fila processada. Enviados: {$totalSent}</info>");
    }
}