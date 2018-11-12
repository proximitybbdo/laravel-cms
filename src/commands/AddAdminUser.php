<?php

namespace BBDO\Cms\Console\Commands;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Console\Command;

class AddAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addadminuser {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds an admin account tot the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $email;

        $credentials = array(
            'email' => $email,
            'password' => $password,
            'name' => $name
        );

        try {
            $user = Sentinel::registerAndActivate($credentials);
            $adminRole = Sentinel::findRoleBySlug('admin');
            $adminRole->users()->attach($user);
        } catch (\Exception $e) {
            $this->error('User creation failed - ' . $e);
        }

        $this->info('User created!');
    }
}
