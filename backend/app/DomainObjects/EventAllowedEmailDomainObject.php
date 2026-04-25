<?php

namespace HiEvents\DomainObjects;

use HiEvents\DomainObjects\Generated\EventAllowedEmailDomainObjectAbstract;

class EventAllowedEmailDomainObject extends EventAllowedEmailDomainObjectAbstract
{
    private bool $isAttendee = false;

    public function getIsAttendee(): bool
    {
        return $this->isAttendee;
    }

    public function setIsAttendee(int|bool $isAttendee): static
    {
        $this->isAttendee = (bool) $isAttendee;
        return $this;
    }
}
