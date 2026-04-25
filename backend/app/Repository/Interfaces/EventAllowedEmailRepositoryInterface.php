<?php

namespace HiEvents\Repository\Interfaces;

use HiEvents\DomainObjects\EventAllowedEmailDomainObject;
use HiEvents\Http\DTO\QueryParamsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @extends RepositoryInterface<EventAllowedEmailDomainObject>
 */
interface EventAllowedEmailRepositoryInterface extends RepositoryInterface
{
    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator;

    public function getStatsByEventId(int $eventId): array;
}
