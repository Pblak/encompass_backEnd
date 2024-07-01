<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->seedOurUsers();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }

    private function seedOurUsers()
    {
        // password
        // $2y$10$KNFV/WnJlgVCzQUedoor0enLGSte1FwHTq.y6sIwRXPs8MxZ1CaBC

        User::create([
            'name' => 'Chris Nowlan',
            'type' => 'admin',
            'email' => 'chrisnowlan321@gmail.com',
//            'password' => '$2y$10$WsQg/YFGixkwLfJf4O8Lk.6o0ece8Meo9vFHdImNm5S0GxBCsya8C',
            'password' => Hash::make('adminPass321'),
        ]);
        User::create([
            'name' => 'Cindy Rudd',
            'email' => 'cinboop@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);


        // !- Important -! //
        User::create([
            'name' => 'Barbara Himel',
            'type' => 'admin',
            'email' => 'himelstx@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);
        User::create([
            'name' => 'Robert Himel',
            'type' => 'admin',
            'email' => 'testingencompass@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);
        // !- Important -! //

        User::create([
            'name' => 'Chris Teacher',
            'type' => 'teacher',
            'email' => 'flipvollc@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

        User::create([
            'name' => 'Admin Account',
            'type' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

        User::create([
            'name' => 'Teacher Account',
            'type' => 'teacher',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

        User::create([
            'name' => 'Parent Account',
            'type' => 'parent',
            'email' => 'parent@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

//        User::factory()->create([
//            'name' => 'Student1 Account',
//            'type' => 'student',
//            'email' => 'student1@gmail.com',
//            'password' => Hash::make('password'),
//        ]);
//        User::factory()->create([
//            'name' => 'Student2 Account',
//            'type' => 'student',
//            'email' => 'student2@gmail.com',
//            'password' => Hash::make('password'),
//        ]);
//        User::factory()->create([
//            'name' => 'teacher1 Account',
//            'type' => 'teacher',
//            'email' => 'teacher1@gmail.com',
//            'password' => Hash::make('password'),
//        ]);
//        User::factory()->create([
//            'name' => 'teacher2 Account',
//            'type' => 'teacher',
//            'email' => 'teacher2@gmail.com',
//            'password' => Hash::make('password'),
//        ]);
//        User::factory()->create([
//            'name' => 'parent Account',
//            'type' => 'parent',
//            'email' => 'parent@gmail.com',
//            'password' => Hash::make('password'),
//        ]);

    }
}
