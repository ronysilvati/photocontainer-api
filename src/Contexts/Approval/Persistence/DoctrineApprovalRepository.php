<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Persistence;

use Doctrine\ORM\EntityRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;

class DoctrineApprovalRepository extends EntityRepository implements ApprovalRepository
{
    /**
     * @param DownloadRequest $request
     * @return DownloadRequest
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function createDownloadRequest(DownloadRequest $request): DownloadRequest
    {
        try {
            $this->_em->persist($request);
            $this->_em->flush();

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação do pedido para acesso!', $e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @param int $user_id
     * @return null|DownloadRequest
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function findDownloadRequest(int $event_id, int $user_id): ?DownloadRequest
    {
        try {
            $request = $this->findOneBy(['event_id' => $event_id, 'user_id' => $user_id]);

            if ($request === null) {
                return null;
            }

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação do pedido para acesso!', $e->getMessage());
        }
    }

    /**
     * @param DownloadRequest $request
     * @return null|DownloadRequest
     * @throws PersistenceException
     */
    public function update(DownloadRequest $request): ?DownloadRequest
    {
        try {
            $this->_em->merge($request);
            $this->_em->flush();

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException('Não é possível autorizar/desautorizar.', $e->getMessage());
        }
    }
}
