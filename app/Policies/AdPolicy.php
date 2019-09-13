<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ad;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user)
    {
        if($user->admin){
            return true;
        }

    }
    public function show(?User $user, Ad $ad)
    {
        if ($user && $user->id == $ad->user_id){
            return true;
        }
        return $ad->active;
    }


    public function manage(User $user, Ad $ad)
    {
        return $user->id == $ad->user_id;
    }


}
