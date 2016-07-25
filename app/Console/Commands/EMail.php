<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mail;

class EMail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'email';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send out emails with a reminder';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		  $email = "shashank.srikant@gmail.com";
                $data_to_push = [
			'v_name' => 'name',
			'v_email' => $email,
			'i_phone_number' => '1234',
			'v_password' => '123',
			'v_password_unenc' => date("H.i.s")
		];
		$sent=Mail::send('emails.activation',$data_to_push, function($message) use($email){
		$message->to($email)->subject('Seva Setu: Reminder');
		});
	}

}
