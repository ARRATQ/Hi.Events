<?php

namespace HiEvents\Resources\EventAllowedEmail;

use HiEvents\DomainObjects\EventAllowedEmailDomainObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EventAllowedEmailDomainObject
 */
class EventAllowedEmailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'event_id' => $this->getEventId(),
            'email' => $this->getEmail(),
            'created_at' => $this->getCreatedAt(),
        ];
    }
}
