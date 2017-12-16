<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Domain;

interface ApprovalRepository
{
    /**
     * @param DownloadRequest $request
     * @return DownloadRequest
     */
    public function createDownloadRequest(DownloadRequest $request): DownloadRequest;

    /**
     * @param int $event_id
     * @param int $user_id
     * @return null|DownloadRequest
     */
    public function findDownloadRequest(int $event_id, int $user_id): ?DownloadRequest;

    /**
     * @param DownloadRequest $request
     * @return null|DownloadRequest
     */
    public function update(DownloadRequest $request): ?DownloadRequest;
}
