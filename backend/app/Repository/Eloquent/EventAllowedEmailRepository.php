<?php

namespace HiEvents\Repository\Eloquent;

use HiEvents\DomainObjects\EventAllowedEmailDomainObject;
use HiEvents\DomainObjects\Generated\EventAllowedEmailDomainObjectAbstract;
use HiEvents\Http\DTO\QueryParamsDTO;
use HiEvents\Models\EventAllowedEmail;
use HiEvents\Repository\Interfaces\EventAllowedEmailRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<EventAllowedEmailDomainObject>
 */
class EventAllowedEmailRepository extends BaseRepository implements EventAllowedEmailRepositoryInterface
{
    protected function getModel(): string
    {
        return EventAllowedEmail::class;
    }

    public function getDomainObject(): string
    {
        return EventAllowedEmailDomainObject::class;
    }

    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator
    {
        $where = [
            [EventAllowedEmailDomainObjectAbstract::EVENT_ID, '=', $eventId],
        ];

        $this->model = $this->model->orderBy('created_at', 'desc');

        return $this->paginateWhere(
            where: $where,
            limit: $params->per_page,
            page: $params->page,
        );
    }
}
