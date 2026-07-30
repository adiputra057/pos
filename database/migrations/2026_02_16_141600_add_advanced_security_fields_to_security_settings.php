<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('security_settings', function (Blueprint $table) {
            $table->integer('max_login_attempts')->default(5)->after('enable_activity_log');
            $table->integer('lockout_duration')->default(15)->after('max_login_attempts');
            $table->integer('min_password_length')->default(8)->after('lockout_duration');
            $table->boolean('require_password_complexity')->default(false)->after('min_password_length');
            $table->integer('password_expiry_days')->default(0)->after('require_password_complexity');
            $table->text('allowed_ips')->nullable()->after('password_expiry_days');
            $table->time('operational_hours_start')->nullable()->after('allowed_ips');
            $table->time('operational_hours_end')->nullable()->after('operational_hours_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_settings', function (Blueprint $table) {
            $table->dropColumn([
                'max_login_attempts',
                'lockout_duration',
                'min_password_length',
                'require_password_complexity',
                'password_expiry_days',
                'allowed_ips',
                'operational_hours_start',
                'operational_hours_end'
            ]);
        });
    }
};
