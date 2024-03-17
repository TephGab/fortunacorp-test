<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Vonage\SMS\Message\SMS;
use Vonage\Client\Credentials\Basic;
use GuzzleHttp\Client as GuzzleClient;

class SendWelcomeUserSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set the path to the cacert.pem file
        $cacertPath = storage_path('certificates/cacert.pem');

        $basic = new Basic(env("VONAGE_KEY"), env("VONAGE_SECRET"));
        $url = 'https://fortuna-corp.com';
        $recipientNumber = $this->user->phone;
        $userName = $this->user->first_name;
        $senderName = env("VONAGE_SMS_SENDER_NAME");
        $messageContent = "Byenvini $userName! nou kontan genyenw pami nou. klike la pou konkete $url";

        $client = new \Vonage\Client($basic);
        
        // Create a Guzzle HTTP client instance with the CA certificate path
        $guzzleClient = new GuzzleClient(['verify' => $cacertPath,]);

        // Set the Guzzle client in the Vonage client to use configured settings
        $client->setHttpClient($guzzleClient);

       // $response = $client->sms()->send(new SMS($recipientNumber, $senderName, $messageContent));
    }
}
