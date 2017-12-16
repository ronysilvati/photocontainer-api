<?php

namespace Helper;

use PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker;

class TestAtomicWorker implements AtomicWorker
{
    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param callable $transaction
     * @param callable $onException
     * @return bool
     * @throws \Exception
     */
    public function execute(callable $transaction, ?callable $onException = null)
    {
        try {
            return $transaction();
        } catch (\Exception $e) {
            if ($onException) {
                $onException($e);
            } else {
                throw $e;
            }
        }
    }
}