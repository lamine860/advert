<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AdRepository;
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

}
