<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flixtechs:admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make sure there is a user with the admin role';

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
    * Get command arguments.
    *
    * @return array
    */
   protected function getArguments()
   {
       return [
           ['email', InputOption::VALUE_REQUIRED, 'The email of the user.', null],
       ];
   }

    public function fire(): int
    {
       return $this->handle();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('The user does not exist, please create account');
            return 1;
        }

        $user->admin = true;
	$user->save();

        $this->info('This user now has full access of your site');

        return 0;
    } 
}
