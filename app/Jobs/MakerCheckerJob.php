<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\AdminRequest;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Mail\MakerCheckerMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MakerCheckerServiceMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MakerCheckerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $endorsees, $getLoginSender, $adminRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($endorsees, $getLoginSender, $adminRequest)
    {
        // info($endorsees);
        $this->endorsees = $endorsees;
        $this->getLoginSender = $getLoginSender;
        $this->adminRequest = $adminRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->endorsees as $endorsee) {
            $mailData = [
                'title' => 'A Request from ' . $this->getLoginSender->first_name . ' to ' . $endorsee['first_name'],
                'body' => 'Hi ' . $endorsee['first_name'] . '! Top of the day to you, kindly review this request of terminating the contract of Rodger Smith by accepting or declining.',
                'endorsee' => $endorsee,
                'request_id' => $this->adminRequest['id'],
            ];
            Mail::to($endorsee['email'])->send(new MakerCheckerServiceMail($mailData));
        }
    }
}
