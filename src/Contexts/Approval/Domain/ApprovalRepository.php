<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Domain;

interface ApprovalRepository
{
    public function createDownloadRequest(DownloadRequest $request): DownloadRequest;
    public function findDownloadRequest(int $event_id, int $user_id): ?DownloadRequest;
    public function approval(DownloadRequest $request): ?DownloadRequest;
    public function disapproval(DownloadRequest $request): ?DownloadRequest;
    public function findEvent(int $event_id): Event;
    public function findUser(int $publisher_id): User;
}
