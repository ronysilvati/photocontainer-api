<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Notification
{
    /**
     * @var int
     */
    private $approvalWaitList;

    public function setApprovalWaitList(int $approvalWaitList)
    {
        $this->approvalWaitList = $approvalWaitList;
    }

    public function getTotal()
    {
        return $this->approvalWaitList;
    }
}
