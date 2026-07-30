<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    echo "Found user: " . $user->name . " (" . $user->email . ")\n";
    
    $roleName = 'owner';
    $role = \App\Models\Role::firstOrCreate(['name' => $roleName], ['description' => 'Owner']);
    
    if (!$user->roles->contains('name', $roleName)) {
        $user->roles()->attach($role->id);
        echo "Assigned '{$roleName}' role to user.\n";
    } else {
        echo "User already has '{$roleName}' role.\n";
    }
} else {
    echo "No users found in database.\n";
}
