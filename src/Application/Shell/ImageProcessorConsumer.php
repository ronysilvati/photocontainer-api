<?php

namespace PhotoContainer\PhotoContainer\Application\Shell;

use Interop\Queue\PsrContext;
use Intervention\Image\ImageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImageProcessorConsumer extends Command
{
    /**
     * @var ImageManager
     */
    private $manager;

    /**
     * @var \Interop\Queue\PsrConsumer
     */
    private $consumer;

    const QUEUE = 'image_processor';

    /**
     * EmailPoolConsumer constructor.
     * @param null $name
     * @param PsrContext $context
     */
    public function __construct($name = null, PsrContext $context)
    {
        $this->manager = new ImageManager();
        $this->consumer = $context->createConsumer($context->createQueue(self::QUEUE));

        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('image_pool:process')
            ->setDescription('Processa as imagens enviadas pelos fotógrafos.');

        $this->addArgument(
            'timeout',
            InputArgument::OPTIONAL,
            'Tempo (em segundos) em que o processo escuta novas imagens a serem processadas.',
            20
        );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeout = $input->getArgument('timeout');
        $time = time();

        $output->writeln('<info>Escutando fila de imagens...</info>');

        while (true) {
            if ($psrMessage = $this->consumer->receive(1000)) {
                $message = json_decode($psrMessage->getBody());

                if ($message->type === 'watermark') {
                    $this->generateWatermark($message);
                    $output->writeln("<info>Watermark aplicado. Imagem: {$message->watermark_target_file}</info>");
                }

                $this->consumer->acknowledge($psrMessage);
            }

            if ($timeout && (time() - $time) >= $timeout) {
                break;
            }
        }
    }

    public function generateWatermark($message)
    {
        $image = $this->manager
            ->make($message->thumb_target_file)
            ->insert($message->watermark_file, 'center-center', 0, 0);
        $image->save($message->watermark_target_file);
    }
}