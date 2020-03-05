<?php

namespace App\Http\Controllers;

use App\Notifications\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => ['string','required'],
            'email'=> ['email','required'],
            'message' => ['required'],
        ]);

        Notification::route('mail', 'quez2223@gmail.com')
            ->notify(new Contact($data));
    }
}
