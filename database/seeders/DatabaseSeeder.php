<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $names = ["tv shows", "movies", 'sports'];

        foreach ( $names as $name )
        {
            $exists = Survey::where("name",$name)->count();
            if( !$exists ){
                Survey::create([
                    "name" => $name
                ]);
            }
        }
    }
}
