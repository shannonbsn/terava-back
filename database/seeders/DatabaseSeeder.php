<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile; // Ajout de l'import pour le modèle Profile
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Location;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Générer 10 utilisateurs
        User::factory(20)->create();

        $users = User::all();
        foreach ($users as $user) {
            // eager load the profile relationship
            Profile::factory()->for($user)->create();
        }

        Location::factory()->count(10)->create();
        Trip::factory()->count(10)->create();


        // Générer 20 profils fictifs
        // Profile::factory(20)->create();

    }
}
