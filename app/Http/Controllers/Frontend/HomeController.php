<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\FundingSource;
use App\Models\Image;
use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// When logged in

class HomeController
{
    
    public function index()
    {
        $myprojects = [];

        if (Auth::check()) {
            $myprojects = Project::where('user_id', Auth::id())->get();
        }

        $projects = Project::orderBy('name')->with(['user'])->get();
        
        $topics = Topic::orderBy('order', 'asc')->withCount('projects')->get();

        $featuredcount = Project::where('featured',1)->count();
      
        return view('frontend.home', compact('projects', 'myprojects', 'topics', 'featuredcount'));
    }
}
