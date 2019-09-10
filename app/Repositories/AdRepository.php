<?php
namespace App\Repositories;
use App\Models\Ad;
use Carbon\Carbon;
class AdRepository
{
    /**
     * Search.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function search($request)
    {
        $ads = Ad::query();
        if($request->region != 0) {
            $ads = Ad::whereHas('region', function ($query) use ($request) {
                $query->where('regions.id', $request->region);
            })->when($request->departement != 0, function ($query) use ($request) {
                return $query->where('departement', $request->departement);
            })->when($request->commune != 0, function ($query) use ($request) {
                return $query->where('commune', $request->commune);
            });
        }
        if($request->category != 0) {
            $ads->whereHas('category', function ($query) use ($request) {
                $query->where('categories.id', $request->category);
            });
        }
        return $ads->with('category', 'photos')
            ->whereActive(true)
            ->latest()
            ->paginate(3);
    }

    public function getPhotos(Ad $ad):array
    {
        return $ad->photos;
    }

    public function getById(int $id)
    {
        return Ad::findOrFail($id);
    }

    public function create($data)
    {
        return Ad::create($data);
    }
}