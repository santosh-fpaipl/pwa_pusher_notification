<?php

namespace App\Http\Controllers;

use App\Models\PushNotification;
use Illuminate\Http\Request;
use App\Notifications\WebPushNotification;
use App\Models\User;

class PushController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function store(Request $request){

        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $user = auth()->user();
        $contentEncoding = null;
        $endpoint = $request->endpoint;

        $PushNotification = PushNotification::findByEndpoint($endpoint);

        if ($PushNotification && ! $user->ownsPushNotification($PushNotification)) {
            $PushNotification->delete();
        }

        // && $this->user->ownsPushNotification($PushNotification)
        if ($PushNotification && $user->ownsPushNotification($PushNotification)) {
            $PushNotification->public_key = $request->keys['p256dh'];
            $PushNotification->auth_token = $request->keys['auth'];
            $PushNotification->content_encoding = $contentEncoding;
            $PushNotification->save();
        } else {
            PushNotification::create([
                'subscribable_type' => 'App\Models\User',
                'subscribable_id' => $user->id,
                'endpoint' => $endpoint,
                'public_key' => $request->keys['p256dh'],
                'auth_token' => $request->keys['auth'],
                'content_encoding' => $contentEncoding,
            ]);
        }
        return response()->json(['success' => true], 200);
    }

    public function push(Request $request){
        if ($request->has('users')) {
            foreach ($request->users as $userId) {
                $user = User::findOrFail($userId);
                $user->notify(new WebPushNotification());
            }
        }
        return redirect()->back();
    }
    
}