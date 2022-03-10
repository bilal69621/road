<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          [
              'name'=>'battery',
              'amount'=>74,
          ] , [
              'name'=>'tire',
              'amount'=>74,
          ]  , [
              'name'=>'tow',
              'amount'=>74,
          ]  , [
              'name'=>'lockout',
              'amount'=>74,
          ]  , [
              'name'=>'fuel',
              'amount'=>74,
          ]  , [
              'name'=>'winch',
              'amount'=>74,
          ]
        ];
        foreach ($data as $d){
            \App\AllService::create($d);
        }
    }
}
