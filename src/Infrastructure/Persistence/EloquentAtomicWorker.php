<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence;

use Illuminate\Database\Capsule\Manager as DB;

class EloquentAtomicWorker implements AtomicWorker
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
            DB::beginTransaction();
            $result = $transaction();
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollback();

            if ($onException) {
                $onException($e);
            } else {
                throw $e;
            }
        }
    }
}