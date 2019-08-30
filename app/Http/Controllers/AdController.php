<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Region;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\AdRepository;

class AdController extends Controller
{
    /**
     * Ad repository.
     *
     * @var App\Repositories\AdRepository
     */
    protected $adRepository;
    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }
    
    public function search(Request $request)
    {
        setlocale (LC_TIME, 'fr_FR');
        $ads = $this->adRepository->search($request);
        return view('partials.ads', compact('ads'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $regionSlug = null, $departementCode = null,$communeCode = null)
    {
        $categories = Category::select('name', 'id')->oldest('name')->get();
        $regions = Region::select('id','code','name','slug')->oldest('name')->get();
        $region = $regionSlug ? Region::whereSlug($regionSlug)->firstOrFail() : null;
        $page = $request->query('page', 0);
        return view('adsvue', compact('categories', 'regions', 'region', 'departementCode', 'communeCode', 'page'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ad $ad)
    {
        $this->authorize('show', $ad);
        $photos = $ad->photos;
        return view('ad', compact('ad', 'photos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
