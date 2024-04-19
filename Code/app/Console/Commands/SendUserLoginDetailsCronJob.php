<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Mail\sendlogindetailsEmail;

class SendUserLoginDetailsCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:logindetails';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send multiple users login details via email.';
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
     * @return int
     */
    public function handle()
    {
        $records = DB::table('cron_job_send_login_details')->where('is_sent', 0)->get();

        $records = DB::table('users')
                    ->select('users.id', 'users.email', 'users.firstname', 'users.lastname', 'users.normal_password', 'cron_job_send_login_details.id AS cronJobSendMailId')
                    ->join('cron_job_send_login_details', 'users.id', '=', 'cron_job_send_login_details.user_id')
                    ->where('cron_job_send_login_details.is_sent', 0)
                    ->get();

        if(count($records))
        {
            foreach($records as $record)
            {
                //Send email
                $data = ['ToEmailAddr'=>$record->email, 'ToUserName'=>$record->firstname, 'password' =>$record->normal_password];
                try {
                    Mail::to($record->email)->send(new sendlogindetailsEmail($data));
                    DB::table('cron_job_send_login_details')->where('id', $record->cronJobSendMailId)->update(['is_sent' =>1]);
                } catch (\Exception $e) {
                    $failureReason = $e->getMessage();
                    DB::table('cron_job_send_login_details')->where('id', $record->cronJobSendMailId)->update(['failure_reason' =>$failureReason]);
                    // return $e->getMessage();
                }
            }
        }        
         
        $this->info('Successfully sent mails.');
    }
}