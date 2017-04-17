<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

abstract class Email
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var array
     */
    private $to;

    /**
     * @var array
     */
    private $from;

    /**
     * @var string
     */
    private $template;

    /**
     * Email constructor.
     * @param array $data
     * @param string $file
     * @param string $subject
     * @param array $to
     * @param array $from
     */
    public function __construct(array $data, string $file, string $subject, array $to, array $from)
    {
        $this->data = $data;
        $this->file = $file;
        $this->subject = $subject;
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        if ($this->template == null && is_file($this->file)) {
            $this->template = file_get_contents($this->file);
        }

        if (empty($this->template)) {
            throw new \DomainException('Não foi possível abrir o layout do email.');
        }

        return $this->template;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return array
     */
    public function getFrom(): array
    {
        return $this->from;
    }
}