<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageAd;
use App\Notifications\AdMessage;
use App\Http\Requests\EmailUpdate;
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


    public function index(Request $request)
    {
        $ads = $this->adRepository->getByUser($request->user());
        $adAttenteCount = $this->adRepository->noActiveCount($ads);
        $adActivesCount = $this->adRepository->activeCount($ads);
        $adPerimesCount = $this->adRepository->obsoleteCount($ads);
        return view('user.index', compact('adActivesCount', 'adPerimesCount', 'adAttenteCount'));
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

    public function actives(Request $request)
    {
        $ads = $this->adRepository->active($request->user(), 5);
        return view('user.actives', compact('ads'));
    }

    public function attente(Request $request)
    {
        $ads = $this->adRepository->attente($request->user(), 5);
        return view('user.waiting', compact('ads'));
    }

    public function obsoletes(Request $request)
    {
        $ads = $this->adRepository->obsoleteForUser($request->user(), 5);
        return view('user.obsoletes', compact('ads'));
    }
    public function emailEdit()
    {
        return view('user.email');
    }
    public function emailUpdate(EmailUpdate $request)
    {
        auth()->user()->email = $request->email;
        auth()->user()->save();
        $request->session()->flash('status', "Votre email a bien été mis à jour.");
        return back();
    }


    public function data()
    {
        $user = auth()->user();
        return view('user.data', compact('user'));
    }
}
