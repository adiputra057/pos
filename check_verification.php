<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- User Verification Status ---\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "User: " . str_pad($user->email, 25) . " | Verified At: " . ($user->email_verified_at ?? 'NULL') . "\n";
}
