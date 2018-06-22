<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

abstract class Email
{
    /**
     * @var array|null
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
    protected $template;

    /**
     * Email constructor.
     * @param array|null $data
     * @param string $file
     * @param string $subject
     * @param array $to
     * @param array|null $from
     */
    public function __construct(?array $data, string $file, string $subject, array $to, ?array $from = null)
    {
        $this->data = $data;
        $this->file = $file;
        $this->subject = $subject;
        $this->to = $to;

        if (!$from) {
            $from = [
                'name' => getenv('PHOTOCONTAINER_EMAIL_NAME'),
                'email' => getenv('PHOTOCONTAINER_EMAIL')
            ];
        }

        $this->from = $from;
    }

    /**
     * @return array
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return string
     * @throws \DomainException
     */
    public function getTemplate(): string
    {
        if ($this->template === null && is_file($this->file)) {
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
