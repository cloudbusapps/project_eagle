<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\User;
use App\Notifications\OvertimeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class NotificationController extends Controller
{
    public function sendOvertimeEmail($Id)
    {
        // SELECT THE APPROVERS DATA 
        $user = User::where('email', 'cieldantalion@gmail.com')->first();
        //  SELECT EMAIL OF CC
        $cc = User::inRandomOrder()->limit(2)->get();

        // GETS THE OVERTIME DETAILS
        $data = OvertimeRequest::where('Id', $Id)->first();

        $project = [
            'cc' => $cc,
            'greeting' => 'Hi ' . $user->FirstName . ',',
            'body' => 'This is my overtime request. below are the details:',
            'thanks' => 'Thank you!',
            'agenda' => $data->Agenda,
            'date' => $data->Date,
            'timeIn' => $data->TimeIn,
            'timeOut' => $data->TimeOut,
            'reason' => $data->Reason,
            'userName' => $data->createdBy->FirstName . ' ' . $data->createdBy->LastName
        ];

        Notification::send($user, new OvertimeEmail($project));
    }
    // ADDED
    public function updateNotif($Id)
    {
        $notification = DB::table('notifications')
            ->where('id', '=', $Id)
            ->where('read_at', '=', null)
            ->update(['read_at' => now()]);
        if ($notification > 0) {
            return redirect()->back();
        }
    }
}
