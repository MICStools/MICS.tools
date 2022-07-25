<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FundingSource;
use App\Models\Image;
use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// When not logged in

class HomeController extends Controller
{
    
    public function index()
    {
        $myprojects = []; // not logged in

        $projects = Project::orderBy('name')->with(['user'])->get();
      
        $topics = Topic::orderBy('order', 'asc')->withCount('projects')->get();

        $featuredcount = Project::where('featured',1)->count();
      
        return view('frontend.home', compact('projects', 'myprojects', 'topics', 'featuredcount'));
    }
}
