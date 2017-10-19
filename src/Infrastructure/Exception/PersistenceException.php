<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Exception;

use Throwable;

class PersistenceException extends \Exception
{
    /**
     * @var string
     */
    private $infraLayerError;

    /**
     * PersistenceException constructor.
     * @param string $message
     * @param int $infraLayerError
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $infraLayerError, $code = 0, Throwable $previous = null) {
        $this->infraLayerError = $infraLayerError;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getInfraLayerError(): string
    {
        return $this->infraLayerError;
    }
}
