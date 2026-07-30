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
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('printer_name', 100);
            $table->string('printer_type', 50)->default('receipt'); // 'receipt', 'kitchen', 'bar'
            $table->boolean('is_default')->default(false);
            $table->string('paper_size', 20)->default('58mm'); // '58mm', '80mm'
            $table->boolean('auto_print')->default(true);
            $table->integer('print_copies')->default(1);
            $table->string('connection_type', 50)->default('usb'); // 'usb', 'network', 'bluetooth'
            $table->string('ip_address', 45)->nullable();
            $table->integer('port')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default printer
        DB::table('printer_settings')->insert([
            'printer_name' => 'Default Receipt Printer',
            'printer_type' => 'receipt',
            'is_default' => true,
            'paper_size' => '58mm',
            'auto_print' => true,
            'print_copies' => 1,
            'connection_type' => 'usb',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_settings');
    }
};
