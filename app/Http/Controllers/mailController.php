<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Emails;

class mailController extends Controller
{
    public function send_feedback(Request $request)
    {
        $validated = $request->validate([
            'info.email' => 'required|email',
            'info.flName' => 'required',
            'info.message' => 'required',
            'info.subject' => 'required',
        ]);
        // insert email
        $new_email = new Emails();
        $new_email->subject = $request->info['subject'];
        $new_email->contact_email = $request->info['email'];
        $new_email->name_of_sender = $request->info['flName'];
        $new_email->text = $request->info['message'];
        $new_email->save();

        $data = ['email' => $request->info['email'], 'FullName' => $request->info['flName'], 'subject' => $request->info['subject'], 'message' => $request->info['message']];
        $data['message'] = 'Mesaj: ' . $data['message'] . ' (Informatie primita de la NP: ' . $data['FullName'] . ' Email: ' . $data['email'] . ')';

        Mail::raw($data['message'], function ($message) use ($data) {
            $message->from('studbudcompany@gmail.com');
            $message->to('studbudcompany@gmail.com');
            $message->subject($data['subject']);
        });
    }
}
