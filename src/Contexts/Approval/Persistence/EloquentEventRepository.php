<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\DownloadRequest as RequestModel;

class EloquentEventRepository implements ApprovalRepository
{

    public function createDownloadRequest(DownloadRequest $request): DownloadRequest
    {
        try {
            $requestModel = new RequestModel();
            $requestModel->event_id = $request->getEventId();
            $requestModel->user_id = $request->getUserId();
            $requestModel->authorized = $request->isAuthorized();
            $requestModel->visualized = $request->isVisualized();
            $requestModel->active = $request->isActive();
            $requestModel->save();

            $request->changeId($requestModel->id);

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação do pedido para acesso!");
        }
    }

    public function findDownloadRequest(int $event_id, int $user_id): ?DownloadRequest
    {
        try {
            $request = RequestModel::where('user_id', $user_id)
                ->where('event_id', $event_id)
                ->first();

            if ($request == null) {
                return null;
            }

            return new DownloadRequest(
                $request->id,
                $request->event_id,
                $request->user_id,
                $request->authorized,
                $request->visualized,
                $request->active
            );
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação do pedido para acesso!");
        }
    }

    public function approval(DownloadRequest $request): ?DownloadRequest
    {
        try {
            $requestModel = RequestModel::find($request->getId());
            $requestModel->authorized = true;
            $requestModel->active = false;
            $requestModel->save();

            $request->changeAuthorized(true);
            $request->changeActive(false);

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException("Não foi possível autorizar o pedido.");
        }
    }

    public function disapproval(DownloadRequest $request): ?DownloadRequest
    {
        try {
            $requestModel = RequestModel::find($request->getId());
            $requestModel->authorized = false;
            $requestModel->active = false;
            $requestModel->save();

            $request->changeAuthorized(false);
            $request->changeActive(false);

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException("Não foi possível autorizar o pedido.");
        }
    }
}