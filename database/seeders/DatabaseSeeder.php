<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Instrument;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Teacher;
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
//        $this->seedInstrument();
        // \App\Models\User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }

    private function seedInstrument(): void
    {
        // Create 10 instruments
        // ['Guitar', 'Piano', 'Drums', 'Bass', 'Violin', 'Cello', 'Trumpet',
        //                'Saxophone', 'Clarinet', 'Flute', 'Trombone', 'French Horn', 'Tuba', 'Oboe', 'Bassoon', 'Viola',
        //                'Ukulele', 'Banjo', 'Mandolin', 'Harp', 'Accordion', 'Bagpipes', 'Organ', 'Harmonica', 'Recorder',
        //                'Xylophone', 'Marimba', 'Steel Drums', 'Synthesizer', 'Theremin', 'Vocals', 'Other']
        // create all these instruments with images for each one
        $instruments = [
            'Guitar', 'Piano', 'Drums', 'Bass', 'Violin', 'Cello', 'Trumpet',
            'Saxophone', 'Clarinet', 'Flute', 'Trombone', 'French Horn', 'Tuba', 'Oboe', 'Bassoon', 'Viola',
            'Ukulele', 'Banjo', 'Mandolin', 'Harp', 'Accordion', 'Bagpipes', 'Organ', 'Harmonica', 'Recorder',
            'Xylophone', 'Marimba', 'Steel Drums', 'Synthesizer', 'Theremin', 'Vocals', 'Other'
        ];

        foreach ($instruments as $name) {
            Instrument::create(['name' => $name]);
        }
    }

    private function seedOurUsers()
    {
        // password
        // $2y$10$KNFV/WnJlgVCzQUedoor0enLGSte1FwHTq.y6sIwRXPs8MxZ1CaBC

        User::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'type' => 'admin',
            'email' => 'admin@gmail.com',
//            'password' => '$2y$10$WsQg/YFGixkwLfJf4O8Lk.6o0ece8Meo9vFHdImNm5S0GxBCsya8C',
            'password' => Hash::make('adminPass321'),
        ]);
        Teacher::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'email' => 'cinboop@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);


        // !- Important -! //
        Parents::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'email' => 'himelstx@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);
        Teacher::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'email' => 'Himel@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);
        // !- Important -! //

        Teacher::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'email' => 'flipvollc@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

        Parents::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'email' => 'Parents2@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

        Parents::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
            'email' => 'Parents3@gmail.com',
            'password' => Hash::make('adminPass321'),
        ]);

        Student::create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            // put a random parent_id that acutally exists
            'parent_id' => Parents::all()->random()->id,
            'infos' => [
                'phone1' => fake()->phoneNumber,
                'phone2' => fake()->phoneNumber,
                'address' => [
                    'street' => fake()->streetAddress,
                    'city' => fake()->city,
                    'state' => fake()->state,
                    'zip' => fake()->postcode
                ]
            ],
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
