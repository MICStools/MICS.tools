@extends('layouts.frontend')
@section('content')

<div class="container">
    <div class="row">
        <div class="topnav col-2">
            <nav>
                <ul>
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://about.mics.tools">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#projects">Project catalogue</a></li>
                
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
                    </li>
                    @if(Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <h5 style="margin-top: 34px">Your projects</h5>
                    @if ($myprojects)
                        @foreach($myprojects as $key => $project)
                            <li class="nav-item">
                                <a class="nav-link" href="/projects/{{ $project->slug }}"><img src="{{ $project->logo ? $project->logo->getUrl('thumb') : '/css/defaultlogo.png' }}" width="25" /> {{ Str::limit($project->shortname, 10) }}</a> 
                            </li>
                        @endforeach
                    @endif
                @endif
                <li class="nav-item"><a class="btn btn-primary p-2" href="projects/create"><i class="fa fa-plus" aria-hidden="true"></i> Create project</a></li>

                </ul>
            </nav>
        </div>

        @include('partials.welcome')
    </div>
</div>

<div class="container p-0">
    <div class="card">
        <div id="projects" class="card-header">
            Project catalogue <small class="text-muted">- Take a look at other projects and their impact</small>
        </div>
        
        <div class="row">
            <div class="bottomnav col-2">

                <nav>
                    <h6>Sort projects by:</h6>
                    
                    <ul id="topiclist">
                        <li>
                            <input type="radio" id="topic_showall" name="topicradios" value="showall" checked onchange="showTopics(this)">
                            <label for="topic_showall">All projects</label>
                        </li>
                        @foreach($topics as $topic)
                            <li>
                                <input type="radio" id="topic_{{ $topic->slug }}" name="topicradios" value="{{ $topic->slug }}" onchange="showTopics(this)">
                                <label for="topic_{{ $topic->slug }}">{{ $topic->name }} <small>({{ $topic->projects_count }})</small></label>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            <script>
                function showTopics(radio) {
                    // Show all or just our ones?
                    if ('topic_showall' == radio.id) {
                        var lisToShow = document.getElementsByClassName("projectbox");
                        for(var i = 0; i < lisToShow.length; i++){
                            //lisToHide[i].style.visibility = "hidden"; // or
                            lisToShow[i].style.display = "revert"; // depending on what you're doing
                        }
                    } else {
                        // Hide all projects
                        var lisToHide = document.getElementsByClassName("projectbox");
                        for(var i = 0; i < lisToHide.length; i++){
                            //lisToHide[i].style.visibility = "hidden"; // or
                            lisToHide[i].style.display = "none"; // depending on what you're doing
                        }

                        // Show just ours
                        var lisToShow = document.getElementsByClassName(radio.id);
                        for(var i = 0; i < lisToShow.length; i++){
                            //lisToHide[i].style.visibility = "hidden"; // or
                            lisToShow[i].style.display = "revert"; // depending on what you're doing
                        }
                    }
                }
            </script>
            
            <div class="col projects">
        
                @if ($projects)
                    @if ($featuredcount)
                        <h6>Featured projects</h6>
                        <ul>
                            @foreach($projects as $key => $project)
                                @if($project->featured)
                                    @php
                                        $topiclist = "";
                                        foreach ($project->topics as $topic) {
                                            $topiclist .= "topic_" . $topic->slug . " ";
                                        }
                                    @endphp

                                    <li class="projectbox {{ $topiclist }}">
                                        <div class="bannerwrapper"><img loading="lazy" class="projectbanner" src="{{ $project->banner ? $project->banner->getUrl() : '/css/stock_banner_'. rand(1,6) .'.png' }}" alt=""></div>
                                        <div class="logowrapper"><img loading="lazy" class="projectlogo" src="{{ $project->logo ? $project->logo->getUrl() : '/css/defaultlogo.png' }}" alt=""></div>
                                        <a class="btn btn-info" href="/projects/{{$project->slug}}">{{ Str::limit($project->shortname, 16) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    
                        <hr />
                    @endif
                    <h6>Project list</h6>
                    <ul>
                        @foreach($projects as $key => $project)
                            @if(!$project->featured)
                                @php
                                    $topiclist = "";
                                    foreach ($project->topics as $topic) {
                                        $topiclist .= "topic_" . $topic->slug . " ";
                                    }
                                @endphp
                                
                                <li class="projectbox {{ $topiclist }}">
                                    <div class="bannerwrapper"><img class="projectbanner" src="{{ $project->banner ? $project->banner->getUrl() : '/css/stock_banner_'. rand(1,6) .'.png' }}" alt=""></div>
                                    <div class="logowrapper"><img class="projectlogo" src="{{ $project->logo ? $project->logo->getUrl() : '/css/defaultlogo.png' }}" alt=""></div>
                                    <a class="btn btn-info" href="/projects/{{$project->slug}}">{{ Str::limit($project->shortname, 16) }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                
                @endif
            </div>
        
        </div>
    </div>
</div>
@endsection