<?php

use Illuminate\Database\Seeder;

class HowlingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = File::get(storage_path() . '/jsons/wolf_how.json');
        $users = json_decode($users);
        foreach ($users as $user) {
            $user=collect($user)->toArray();
            $data=[
                "username"=>$user['username'],
                "user_id"=>$user['user id'],
                "name"=>$user['name'],
                "group"=>$user["group"],
                "group_id"=> $user['group id']
            ];
            \App\GroupUsers::create($data);
        }
    }
}

