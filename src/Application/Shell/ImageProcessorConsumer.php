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
     * In milliseconds
     * @var int
     */
    const TIMEOUT = 50;

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
    protected function configure(): void
    {
        $this
            ->setName('queue_process:images')
            ->setDescription('Processa as imagens enviadas pelos fotógrafos.');

        $this->addArgument(
            'timeout',
            InputArgument::OPTIONAL,
            'Tempo (em segundos) em que o processo escuta novas imagens a serem processadas.',
            61
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
            try {
                if ($psrMessage = $this->consumer->receive(self::TIMEOUT)) {
                    $message = json_decode($psrMessage->getBody());

                    $this->generateWatermark($message);

                    $this->consumer->acknowledge($psrMessage);
                }
            } catch (\Exception $e) {
                $output->writeln('<info>'.$e->getMessage().'</info>');
            }

            if ($timeout && (time() - $time) >= $timeout) {
                break;
            }
        }
    }

    /**
     * @param $message
     */
    public function generateThumb($message): void
    {
        $image = $this->manager
            ->make($message->protected_target_file)->resize(null, 847, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save($message->thumb_target_file, 30);
    }

    /**
     * @param $message
     */
    public function generateWatermark($message): void
    {
        $image = $this->manager
            ->make($message->thumb_target_file)
            ->insert($message->watermark_file, 'center-center', 0, 0);
        $image->save($message->watermark_target_file);
    }
}