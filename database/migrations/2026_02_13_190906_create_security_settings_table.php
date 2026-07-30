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
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('session_timeout')->default(30); // minutes
            $table->boolean('auto_logout_on_close')->default(false);
            $table->boolean('require_pin_for_void')->default(true);
            $table->boolean('require_pin_for_refund')->default(true);
            $table->boolean('require_pin_for_delete')->default(false);
            $table->decimal('large_transaction_threshold', 15, 2)->default(1000000);
            $table->boolean('require_approval_large_transaction')->default(false);
            $table->boolean('enable_activity_log')->default(true);
            $table->timestamps();
        });

        // Insert default security settings
        DB::table('security_settings')->insert([
            'session_timeout' => 30,
            'auto_logout_on_close' => false,
            'require_pin_for_void' => true,
            'require_pin_for_refund' => true,
            'require_pin_for_delete' => false,
            'large_transaction_threshold' => 1000000,
            'require_approval_large_transaction' => false,
            'enable_activity_log' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};
