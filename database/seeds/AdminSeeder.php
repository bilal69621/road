<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ADMIN = [
            [
                'full_name'=>'Sardar Testing Account',
                'email'=>'sardar@gmail.com',
                'password'=>\Illuminate\Support\Facades\Hash::make('11223344'),
            ]
        ];
        foreach ($ADMIN as $DATA){
            \App\Admin::create($DATA);
        }
    }
}
