<?php

namespace HiEvents\Http\Actions\EventAllowedEmails;

use HiEvents\DomainObjects\EventAllowedEmailDomainObject;
use HiEvents\DomainObjects\EventDomainObject;
use HiEvents\Http\Actions\BaseAction;
use HiEvents\Http\DTO\QueryParamsDTO;
use HiEvents\Repository\Interfaces\EventAllowedEmailRepositoryInterface;
use HiEvents\Resources\EventAllowedEmail\EventAllowedEmailResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetEventAllowedEmailsAction extends BaseAction
{
    public function __construct(
        private readonly EventAllowedEmailRepositoryInterface $allowedEmailRepository,
    ) {
    }

    public function __invoke(Request $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $emails = $this->allowedEmailRepository->findByEventId(
            $eventId,
            QueryParamsDTO::fromArray($request->query->all()),
        );

        return $this->filterableResourceResponse(
            resource: EventAllowedEmailResource::class,
            data: $emails,
            domainObject: EventAllowedEmailDomainObject::class,
        );
    }
}
