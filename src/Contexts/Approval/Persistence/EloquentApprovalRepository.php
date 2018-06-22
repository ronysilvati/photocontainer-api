<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\User;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\DownloadRequest as RequestModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User as UserModel;


class EloquentApprovalRepository implements ApprovalRepository
{
    /**
     * @param DownloadRequest $request
     * @return DownloadRequest
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Erro na criação do pedido para acesso!', $e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @param int $user_id
     * @return null|DownloadRequest
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Erro na criação do pedido para acesso!', $e->getMessage());
        }
    }

    /**
     * @param DownloadRequest $request
     * @return null|DownloadRequest
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Não foi possível autorizar o pedido.', $e->getMessage());
        }
    }

    /**
     * @param DownloadRequest $request
     * @return null|DownloadRequest
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Não foi possível autorizar o pedido.', $e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @return Event
     * @throws PersistenceException
     */
    public function findEvent(int $event_id): Event
    {
        try {
            $data = EventModel::find($event_id);
            return new Event($data->title, $data->user_id);
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi buscar o Evento.', $e->getMessage());
        }
    }

    /**
     * @param int $user_id
     * @return User
     * @throws PersistenceException
     */
    public function findUser(int $user_id): User
    {
        try {
            $data = UserModel::find($user_id);
            return new User($data->name, $data->email);
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível buscar o publisher.', $e->getMessage());
        }
    }
}
