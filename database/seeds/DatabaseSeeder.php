<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        // Load json file with permissions here
        // decode it an
        $json = File::get("database/data/CMSroles.json");
        $roles = json_decode($json);

        DB::table('roles')->insert(
            array(
                [
                    'slug' => 'admin',
                    'name' => 'Admin',
                    'permissions' => json_encode( $roles->admin )
                ],
                [
                    'slug' => 'publisher',
                    'name' => 'Publisher',
                    'permissions' => json_encode( $roles->publisher )

                ],
                [
                    'slug' => 'editor',
                    'name' => 'Editor',
                    'permissions' => json_encode( $roles->editor )

                ]
            ));
    }
}
