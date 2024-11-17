<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailer;

class MailCtrl extends Controller
{
    public function index(){
        $mailData = [
            'title' => 'I am a title',
            'body' => 'Sample text',
        ];

        Mail::to('mikeewazowski05@gmail.com')->send(new Mailer($mailData));

        dd('Email sent successfully');
    }
}
