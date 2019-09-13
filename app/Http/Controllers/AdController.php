<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Region;
use App\Models\Upload;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\AdStore;
use Illuminate\Support\Carbon;
use App\Http\Requests\AdUpdate;
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
    public function create(Request $request)
    {
        if(!$request->session()->has('index')) {
            $request->session()->put('index', Str::random(30));
        }
        $categories = Category::select('name', 'id')->oldest('name')->get();
        $regions = Region::oldest('name')->get();
        return view('create', compact('categories', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(AdStore $request)
    {
        $commune = json_decode(file_get_contents('https://geo.api.gouv.fr/communes/' . $request->commune), true);
        $ad = $this->adRepository->create([
            'title' => $request->title,
            'texte' => $request->texte,
            'category_id' => $request->category,
            'region_id' => $request->region,
            'departement' => $request->departement,
            'commune' => $request->commune,
            'commune_name' => $commune['nom'],
            'commune_postal' => $commune['codesPostaux'][0],
            'user_id' => auth()->check() ? auth()->id() : 0,
            'pseudo' => auth()->check() ? auth()->user()->name :$request->pseudo,
            'email' => auth()->check() ? auth()->user()->email : $request->email,
            'limit' => Carbon::now()->addWeeks($request->limit),
            'active' => 0,
        ]);
        if($request->session()->has('index')) {
            $index = $request->session()->get('index');
            Upload::whereIndex($index)->update(['ad_id' => $ad->id, 'index' => 0]);
        }
        return view('adconfirm');
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
        $ad = Ad::findOrFail($id);
        return view('edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ad
     * @return \Illuminate\Http\Response
     */
    public function update(AdUpdate $request, $id)
    {
        $ad = Ad::findOrFail($id);
        $this->authorize('manage', $ad);
        $this->adRepository->update($ad, $request->all());
        $request->session()->flash('status', "L'annonce a bien été modifiée.");
        return back();
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
