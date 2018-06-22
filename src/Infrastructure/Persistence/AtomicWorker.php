<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence;

interface AtomicWorker
{
    public function execute(callable $transaction, ?callable $onException = null);
}