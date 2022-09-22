<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class UserVerificationController extends Controller
{
    public function approve($token)
    {
        $user = User::where('verification_token', $token)->first();
        abort_if(!$user, 404);

        $user->verified           = 1;
        $user->verified_at        = Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        $user->verification_token = null;
        $user->save();

        // make them a User if they're not already
        //$user->roles()->syncWithoutDetaching(2);

        return redirect()->route('login')->with('message', trans('global.emailVerificationSuccess'));
    }
}
