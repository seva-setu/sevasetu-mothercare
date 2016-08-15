<?php namespace App\Console\Commands;

use App\Models\DueList;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mail;

class Remind extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'remind';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send out emails and SMSes with a reminder';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		
		$call_details = $this->send_reminders(date("Y-m-d"));
	}
	
	public function send_email(){
		
	}
	public function send_sms(){
		
	}

	public function send_reminders($date_today){
		include(storage_path().'/sms.php');
		$due_list_obj = new DueList;
		$call_details = $due_list_obj->get_reminder_list();
		
		
		// Type 1 email and sms
		$cc_id_arr = [];
		foreach($call_details['beginweek'] as $val){
			if(array_key_exists($val->cc_id, $cc_id_arr))
				$cc_id_arr[$val->cc_id] []= $val;
			else
				$cc_id_arr[$val->cc_id] = array($val);
		}		
		$this->generate_notifications($cc_id_arr,2);


		// Type 2 email and sms
		$cc_id_arr = [];
		foreach($call_details['midweek'] as $val){
			if(array_key_exists($val->cc_id, $cc_id_arr))
				$cc_id_arr[$val->cc_id] []= $val;
			else
				$cc_id_arr[$val->cc_id] = array($val);
		}		
		$this->generate_notifications($cc_id_arr,4);

		$cc_id_arr = [];
		foreach($call_details['endweek'] as $val){
			if(array_key_exists($val->cc_id, $cc_id_arr))
				$cc_id_arr[$val->cc_id] []= $val;
			else
				$cc_id_arr[$val->cc_id] = array($val);
		}		
		$this->generate_notifications($cc_id_arr,4);


		$cc_id_arr = [];
		foreach($call_details['postweek'] as $val){
			if(array_key_exists($val->cc_id, $cc_id_arr))
				$cc_id_arr[$val->cc_id] []= $val;
			else
				$cc_id_arr[$val->cc_id] = array($val);
		}		
		$this->generate_notifications($cc_id_arr,4);		
		
	}


	public function generate_notifications($cc_id_arr,$sms_id)
	{
		$all_numbers = array();
		$all_names = array();
		foreach($cc_id_arr as $callchamp){
			$all_numbers []= $callchamp[0]->cc_phonenumber;
			$all_names []= $callchamp[0]->cc_name;
			
			//Send SMS to call champion
			if(count($callchamp) > 1){
				send_sms($sms_id, array($callchamp[0]->cc_name, $callchamp[0]->cc_phonenumber, count($callchamp)));
				
				//Send an email
				$email = $callchamp[0]->cc_email;
				$sent=Mail::send('emails.reminder_multiple',
								array('cc_name'=>$callchamp[0]->cc_name,
									  'mother_name'=>$callchamp[0]->mother_name,
									  'count'=>count($callchamp)
									 ), 
								function($message) use($email){
									$message
									->to($email)
									->subject('Seva Setu: Call reminder')
									->bcc('shashank@sevasetu.org');
									}
								);
			}
			else{
				send_sms($sms_id+1, array($callchamp[0]->cc_name,			 $callchamp[0]->cc_phonenumber, 
							$callchamp[0]->mother_name, 
							$callchamp[0]->mother_phonenumber
							)
						);
				
				//Send an email
				$email = $callchamp[0]->cc_email;
				$sent=Mail::send('emails.reminder_single',
								array('cc_name'=>$callchamp[0]->cc_name,
									  'mother_name'=>$callchamp[0]->mother_name,
									  'number'=>$callchamp[0]->mother_phonenumber,
									  'village'=>$callchamp[0]->mother_village
									), 
								function($message) use($email){
									$message
									->to($email)
									->subject('Seva Setu: Call reminder')
									->bcc('shashank@sevasetu.org');
									}
								);
			}
			
			//Send SMS to mother
			//foreach($callchamp as $details)
				//send_sms(6, array($details->mother_phonenumber));
		}

		$email = "shashank@sevasetu.org";
		$sent=Mail::send('emails.reminder_multiple',
							array('cc_name'=>"cron",
								  'mother_name'=>"test",
								  'count'=>serialize($all_numbers)."*****".serialize($all_names)
								 ), 
							function($message) use($email){
								$message
								->to($email)
								->subject('Seva Setu: Call reminder')
								->bcc('shashank@sevasetu.org');
								}
							);
	
	}

	

}
