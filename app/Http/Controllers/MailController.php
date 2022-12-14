<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminRequest;
use Illuminate\Http\Request;
use App\Jobs\MakerCheckerJob;
use App\Mail\FriendRequestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request)
    {

        $getLoginSender = Auth::user();
        $request_type = $request->request_type;
        $endorsee_ids = $request->endorsee_ids;
        //validate
        $endorsees = User::whereIn('id', $endorsee_ids)->select('email', 'first_name')->get()->toArray();
        $data = json_encode([
            'first_name' => $getLoginSender->first_name,
            'last_name' => $getLoginSender->last_name,
            //validate if the admin has a mail
            'email' => $getLoginSender->email,
        ]);

        $adminRequest = AdminRequest::create([
            'request_type' => $request_type,
            'user_id' => $getLoginSender->id,
            'endorsee_id' => json_encode($endorsee_ids),
            'data' => $data
        ]);
//use trait for res
        MakerCheckerJob::dispatch($endorsees, $getLoginSender, $adminRequest->toArray());
        return response()->json([
            'status' => 200,
            'message' => 'Request sent successfully'
        ]);
    }
}
