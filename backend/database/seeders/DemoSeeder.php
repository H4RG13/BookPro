<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@bookpro.test'], [
            'name'     => 'Admin',
            'password' => Hash::make('password'),
        ]);

        $haircut = Service::create(['name' => 'Haircut',   'duration' => 30,  'price' => 25.00]);
        $massage = Service::create(['name' => 'Massage',   'duration' => 60,  'price' => 75.00]);
        $consult = Service::create(['name' => 'Consult',   'duration' => 45,  'price' => 50.00]);

        $alice = Client::create(['name' => 'Alice Smith', 'email' => 'alice@example.com', 'phone' => '555-0101']);
        $bob   = Client::create(['name' => 'Bob Jones',   'email' => 'bob@example.com',   'phone' => '555-0102']);

        Appointment::create([
            'client_id'  => $alice->id,
            'service_id' => $haircut->id,
            'starts_at'  => now()->addDay()->setTime(10, 0),
            'ends_at'    => now()->addDay()->setTime(10, 30),
            'status'     => 'confirmed',
        ]);

        Appointment::create([
            'client_id'  => $bob->id,
            'service_id' => $massage->id,
            'starts_at'  => now()->addDays(2)->setTime(14, 0),
            'ends_at'    => now()->addDays(2)->setTime(15, 0),
            'status'     => 'pending',
        ]);
    }
}
