<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->boolean('hide_promo_code_input')->default(false);
            $table->boolean('prevent_duplicate_email_registrations')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->dropColumn(['hide_promo_code_input', 'prevent_duplicate_email_registrations']);
        });
    }
};
