<?php

namespace HiEvents\Http\Actions\EventAllowedEmails;

use HiEvents\DomainObjects\EventDomainObject;
use HiEvents\Http\Actions\BaseAction;
use HiEvents\Repository\Interfaces\EventAllowedEmailRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class DeleteEventAllowedEmailAction extends BaseAction
{
    public function __construct(
        private readonly EventAllowedEmailRepositoryInterface $allowedEmailRepository,
    ) {
    }

    public function __invoke(Request $request, int $eventId, int $allowedEmailId): Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $allowedEmail = $this->allowedEmailRepository->findById($allowedEmailId);

        if ($allowedEmail === null || $allowedEmail->getEventId() !== $eventId) {
            throw new ResourceNotFoundException(__('Allowed email not found'));
        }

        $this->allowedEmailRepository->deleteById($allowedEmailId);

        return $this->noContentResponse();
    }
}
