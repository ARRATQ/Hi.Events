<?php

namespace HiEvents\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAllowedEmail extends BaseModel
{
    protected $table = 'event_allowed_emails';

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
