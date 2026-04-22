<?php

namespace HiEvents\Http\Actions\EventAllowedEmails;

use HiEvents\DomainObjects\EventDomainObject;
use HiEvents\DomainObjects\Generated\EventAllowedEmailDomainObjectAbstract;
use HiEvents\Http\Actions\BaseAction;
use HiEvents\Http\Request\EventAllowedEmail\CreateEventAllowedEmailsRequest;
use HiEvents\Http\ResponseCodes;
use HiEvents\Repository\Interfaces\EventAllowedEmailRepositoryInterface;
use HiEvents\Resources\EventAllowedEmail\EventAllowedEmailResource;
use Illuminate\Http\JsonResponse;

class CreateEventAllowedEmailsAction extends BaseAction
{
    public function __construct(
        private readonly EventAllowedEmailRepositoryInterface $allowedEmailRepository,
    ) {
    }

    public function __invoke(CreateEventAllowedEmailsRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $created = collect();

        foreach ($request->validated('emails') as $email) {
            $normalizedEmail = strtolower(trim($email));

            $existing = $this->allowedEmailRepository->findFirstWhere([
                EventAllowedEmailDomainObjectAbstract::EVENT_ID => $eventId,
                EventAllowedEmailDomainObjectAbstract::EMAIL => $normalizedEmail,
            ]);

            if ($existing !== null) {
                continue;
            }

            $created->push($this->allowedEmailRepository->create([
                EventAllowedEmailDomainObjectAbstract::EVENT_ID => $eventId,
                EventAllowedEmailDomainObjectAbstract::EMAIL => $normalizedEmail,
            ]));
        }

        return $this->resourceResponse(
            EventAllowedEmailResource::class,
            $created,
            ResponseCodes::HTTP_CREATED,
        );
    }
}
