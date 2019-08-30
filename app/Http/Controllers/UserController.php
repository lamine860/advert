<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageAd;
use App\Notifications\AdMessage;
use App\Repositories\AdRepository;
use App\Repositories\MessageRepository;

class UserController extends Controller
{
    private $messageRepository;
    private $adRepository;

    public function __construct(AdRepository $adRepository, MessageRepository $messageRepository)
    {
        $this->adRepository = $adRepository;
        $this->messageRepository = $messageRepository;
    }

    public function message(MessageAd $request)
    {
        $ad = $this->adRepository->getById($request->id);
        if(auth()->check()){
            $ad->notify(new AdMessage($ad, $request->message, auth()->user()->email));
            return response()->json(['info' => 'Votre message va être rapidement transmis.']);
        }
        $this->messageRepository->create([
            'texte' => $request->message,
            'email' => $request->email,
            'ad_id' => $ad->id,
        ]);
        return response()->json(['info' => 'Votre message a été mémorisé et sera transmis après modération.']);
    }
}
