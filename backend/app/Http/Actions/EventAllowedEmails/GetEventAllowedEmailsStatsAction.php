<?php

namespace HiEvents\Http\Actions\EventAllowedEmails;

use HiEvents\DomainObjects\EventDomainObject;
use HiEvents\Http\Actions\BaseAction;
use HiEvents\Repository\Interfaces\EventAllowedEmailRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetEventAllowedEmailsStatsAction extends BaseAction
{
    public function __construct(
        private readonly EventAllowedEmailRepositoryInterface $allowedEmailRepository,
    ) {
    }

    public function __invoke(Request $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        return $this->jsonResponse($this->allowedEmailRepository->getStatsByEventId($eventId));
    }
}
