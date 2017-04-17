<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Response;

class ApprovalRequestResponse extends DownloadRequestResponse
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
