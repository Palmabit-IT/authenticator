<?php namespace Palmabit\Authentication\Install;

use Illuminate\Console\Command;
use Palmabit\Authentication\Seeds\DbSeeder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Install extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'authentication:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install authentication package.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($db_seeder = null)
	{
    $this->db_seeder = $db_seeder ? $db_seeder : new DbSeeder();
    parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
    $this->call('config:publish', ['package' => 'palmabit/authentication' ] );

    $this->call('migrate', ['--package' => 'palmabit/authentication'] );

    $this->db_seeder->run();

    $this->call('asset:publish');

    $this->info('## Palmabit Authentication Installed successfully ##');
	}
}
