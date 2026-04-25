<?php

namespace HiEvents\Repository\Eloquent;

use HiEvents\DomainObjects\EventAllowedEmailDomainObject;
use HiEvents\Http\DTO\QueryParamsDTO;
use HiEvents\Models\EventAllowedEmail;
use HiEvents\Repository\Interfaces\EventAllowedEmailRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
        $this->model = $this->model
            ->selectRaw(
                'event_allowed_emails.*, EXISTS(
                    SELECT 1 FROM attendees
                    WHERE attendees.email = event_allowed_emails.email
                      AND attendees.event_id = ?
                      AND attendees.deleted_at IS NULL
                ) as is_attendee',
                [$eventId]
            )
            ->when(
                $params->query,
                fn($q) => $q->where('event_allowed_emails.email', 'like', '%' . $params->query . '%')
            )
            ->when(
                $params->query_params?->get('attendees_only'),
                fn($q) => $q->whereExists(
                    fn($sub) => $sub->select(DB::raw(1))
                        ->from('attendees')
                        ->whereColumn('attendees.email', 'event_allowed_emails.email')
                        ->where('attendees.event_id', $eventId)
                        ->whereNull('attendees.deleted_at')
                )
            )
            ->orderBy('event_allowed_emails.created_at', 'desc');

        return $this->paginateWhere(
            where: [['event_allowed_emails.event_id', '=', $eventId]],
            limit: $params->per_page,
            page: $params->page,
        );
    }

    public function getStatsByEventId(int $eventId): array
    {
        $total = EventAllowedEmail::where('event_id', $eventId)->count();

        $matched = EventAllowedEmail::where('event_allowed_emails.event_id', $eventId)
            ->whereExists(
                fn($q) => $q->select(DB::raw(1))
                    ->from('attendees')
                    ->whereColumn('attendees.email', 'event_allowed_emails.email')
                    ->where('attendees.event_id', $eventId)
                    ->whereNull('attendees.deleted_at')
            )
            ->count();

        return [
            'total_invited'   => $total,
            'total_attendees' => $matched,
            'attendance_rate' => $total > 0 ? round($matched / $total * 100, 1) : 0.0,
        ];
    }
}
