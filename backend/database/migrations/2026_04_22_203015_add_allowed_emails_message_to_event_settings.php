<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('event_settings', 'allowed_emails_message')) {
            Schema::table('event_settings', function (Blueprint $table) {
                $table->text('allowed_emails_message')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->dropColumn('allowed_emails_message');
        });
    }
};
