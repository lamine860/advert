<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Notifications\AdRefuse;
use App\Notifications\AdApprove;
use App\Repositories\AdRepository;
use App\Http\Requests\MessageRefuse;
use App\Repositories\MessageRepository;

class AdminController extends Controller
{
    protected $adRepository;
    protected $messagerepository;

    public function __construct(AdRepository $adRepository, MessageRepository $messagerepository)
    {
        $this->adRepository = $adRepository;
        $this->messagerepository = $messagerepository;
        
    }

    public function index()
    {
        $adModerationCount = $this->adRepository->noActiveCount();
        $adPerimesCount = $this->adRepository->obsoleteCount();
        $messageModerationCount = $this->messagerepository->count();
        return view('admin.index', compact('adModerationCount', 'messageModerationCount', 'adPerimesCount'));
    }

    public function ads()
    {
        $adModeration = $this->adRepository->noActive(5);
        return view('admin.ads', compact('adModeration'));
    }


    public function approve(Request $request, Ad $ad)
    {
        $this->adRepository->approve($ad);
        $request->session()->flash('status', "L'annonce a bien été approuvée et le rédacteur va être notifié.");
        $ad->notify(new AdApprove($ad));
        return response()->json(['id' => $ad->id]);
    }


    public function refuse(MessageRefuse $request)
    {
        $ad = $this->adRepository->getById($request->id);
        $ad->notify(new AdRefuse($request->message));
        $this->adRepository->delete($ad);
        $request->session()->flash('status', "L'annonce a bien été refusée et le rédacteur va être notifié.");
        return response()->json(['id' => $ad->id]);
    }


}
