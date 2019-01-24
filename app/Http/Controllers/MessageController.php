<?php

namespace Snapmail\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Snapmail\Http\Requests\MessageRequest;
use Snapmail\Message;

class MessageController extends Controller
{
    public function create(Message $message, MessageRequest $request)
    {
        $data = [
            'key' => str_random(5),
            'email' => $request->email,
            'content' => $request->message
        ];

        $message->key = $data['key'];
        $message->email = $data['email'];
        $message->message = $data['content'];
        $message->save();

        Mail::send('emails.link_email', $data, function ($m) use ($data) {
            $m->to($data['email']);
        });

        return view('validate_form');
    }

    public function get(Message $message, MessageRequest $request)
    {
        $final = $message->where('key', $request->key)->firstOrFail();
        $data = [
            'email' => $final->email,
            'content' => $final->message,
        ];
        $final->delete();
        return view('emails.template', $data);


    }
}
