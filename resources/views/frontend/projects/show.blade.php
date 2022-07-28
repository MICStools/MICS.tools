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

                    @if (Auth::user() && $project->user->id == Auth::user()->id)
                    <li class="nav-item"><a class="btn btn-primary p-2 mt-2" href="/assessment/{{ $project->slug }}/general">Go to assessment</a></li>
                    @endif

                
                </ul>
            </nav>
        </div>
        <div class="col-10 p-0">
            <div class="banner row" style="background: url({{ $project->banner ? $project->banner->getUrl() : '/css/stock_banner_'. rand(1,6) .'.png' }}) no-repeat center center; background-size: cover;">
                <div class="col-5">
                    <h4 id="projectname" title="{{ $project->shortname }}"><span>{{ Str::limit($project->shortname, 10) }}</span></h4>
                    <img id="projectlogo" src="{{ $project->logo ? $project->logo->getUrl() : '/css/defaultlogo.png' }}" alt="" />
                </div>
                <div class="col-7 boxout">
                    <h5>{{ $project->name }}</h5>

                    <dl>
                        @if ($project->startdate)    
                            <dt>Project start date:</dt>
                            <dd>{{ $project->startdate }}</dd>
                        @endif

                        @if ($project->enddate)    
                            <dt>Project end date:</dt>
                            <dd>{{ $project->enddate }}</dd>
                        @endif

                        @if ($project->contact)
                            <dt>Project Contacts:</dt>
                            <dd>{{ $project->contact }} - {{ $project->contactdetails }}</dd>
                        @endif

                        @if ($project->cost)
                            <dt>Project cost:</dt>
                            <dd>{{ $project->cost }}</dd>
                        @endif

                        @if ($project->funding)
                            <dt>Funding amount:</dt>
                            <dd>{{ $project->funding }}</dd>
                        @endif

                        @if ($project->uri)
                            <dt>Project URL:</dt>
                            <dd><a href="{{ $project->uri }}">{{ $project->uri }}</a></dd>
                        @endif

                        <dt>Impact Assesment progress:</dt>
                        <dd>{{ $totalprogress }}% complete</dd>

                    </dl>
                    @if (Auth::user() && $project->user->id == Auth::user()->id)
                    <a class="btn btn-primary p-2" href="/projects/{{ $project->slug }}/edit">Edit</a>
                    @endif
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-7 ml-4 projectdescription">
                    <h6 class="pt-2">Project Description</h6>
                     {!! $project->description !!}   
                </div>
                <div class="col-3">
                
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container p-0">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Impact Assessment Tools: <small class="text-muted">Measure the impact of this project</small>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <h5 class="mt-3">Impact Assesment Tools</h5>
                            <nav>
                                <ul>
                                    @if (Auth::user() && $project->user->id == Auth::user()->id)
                                    
                                        <li><a href="/assessment/{{ $project->slug }}">Domain map</a></li>

                                        @foreach($domains as $domain)
                                        <li><a href="/assessment/{{ $project->slug }}/{{$domain->slug}}">{{ $domain->name }}</a></li>
                                        @endforeach

                                    @endif
                                    <li><a class="btn btn-primary p-2 mt-2" href="/projects/{{ $project->slug }}/summary">View impact report</a></li>
                                    
                                </ul>
                            </nav>
                        </div>
                        
                        <div class="col-6 currentprojectimpact">
                            <h5 class="p-1 mt-3">Current Project Impact</h5>
                            <p class="p-1">Scores are recalulated and updated when “View impact report” is clicked.</p>
                            
                            <div class="col-12 pt-6 mt-4 d-flex justify-content-between">
                                <svg width="0" height="0">
                                    <style>
                                        .large { 
                                            font: 550% sans-serif; 
                                            fill: #fff;
                                            font-weight: lighter;
                                        }
                                        .back {
                                            stroke: #999;
                                            stroke-width: 10px;
                                        }
                                        .legend { 
                                            font: 500% sans-serif; 
                                            fill: #999;
                                        }
                                    </style>
                                </svg>
            
                                @foreach ($project->projectResults as $result)
                    
                                    <svg height="100" viewbox="0 0 600 600">
                                    
                                        <g transform="translate(300,600)">
                                            <circle cy="-300" r="290" fill="none" stroke="#ccc" stroke-width="12"/>
                                            <circle cy="-300" r="270" fill="none" stroke="#ccc" stroke-dasharray="40, 25" stroke-width="12"/>
                                            <text x="0" y="-320" dominant-baseline="middle" text-anchor="middle" class="large back" id="domaintextback">{{ Str::ucfirst($result->domain->slug) }}</text>
                                            <text x="0" y="-200" dominant-baseline="middle" text-anchor="middle" class="large back" id='domainscoreback'>{{ $result->score }}</text>
                                            <circle cy="-300" r="{{ 276 * ($result->score/42) }}" fill="#{{ $result->domain->primarycolour }}" id="chartarea" />
                                            <text x="0" y="-320" dominant-baseline="middle" text-anchor="middle" class="large" id="domaintext">{{ Str::ucfirst($result->domain->slug) }}</text>
                                            <text x="0" y="-200" dominant-baseline="middle" text-anchor="middle" class="large" id='domainscore'>{{ $result->score }}</text>
                                            @if($loop->last)
                                                <text x="120" y="45" dominant-baseline="middle" text-anchor="middle" class="legend">max. 42</text>
                                            @endif
                                        </g>
                                    </svg>
                                    
                                @endforeach
                            </div>
                            <div class="col-12 text-center p-2">
                                <svg height="500" viewbox="0 0 500 500">
                                    <style>
                                        #currentprogress {
                                            fill: #000;
                                            font-family: Montserrat;
                                            font-weight: 100;
                                        }
                    
                                        .totalscore { 
                                            font: 38px sans-serif; 
                                            
                                        }
                    
                                        .score {
                                            font: 30px sans-serif;
                                        }
                    
                                        #currentscore {
                                            font: 60px sans-serif;
                                        }
                                    </style>
                                    <g id="currentprogress" class="circle circle-1" transform="rotate(-0, 250, 250)">
                                        <circle cx="250" cy="250" fill="transparent" r="159.155" stroke="#ccc" stroke-width="60"></circle>
                                        @php
                                            $totalsofar = 0;
                                        @endphp
                                        @foreach ($project->projectResults as $result)
                                            <circle cx="250" cy="250" fill="transparent" r="159.155" stroke="#{{ $result->domain->primarycolour }}" stroke-dasharray="{{ 1000 * $result->percentage }}, {{ 1000 * (1-$result->percentage) }}" stroke-dashoffset="{{ 250 - (1000 * $totalsofar) }}" stroke-width="60"></circle>
                                            @php
                                                $totalsofar += $result->percentage;
                                            @endphp
                                        @endforeach
                    
                                      <text x="50%" y="220" dominant-baseline="middle" text-anchor="middle" class="totalscore">Total Score</text>
                                      <text x="50%" y="300" dominant-baseline="bottom" text-anchor="middle" class="score"><tspan id='currentscore'>{{ $average }}</tspan>/42</text>
                                    </g>
                                </svg>
                            </div>

                        </div>
                        
                        <div class="col-4">
                            <h5 class="p-2 mt-2">Domain Progress</h5>
                            
                            <svg width="0" height="0">
                                <style>
                                    .name { 
                                        font: 350% sans-serif;                                         
                                        font-weight: lighter;
                                    }
                                    .fraction { 
                                        font: 300% sans-serif;                                         
                                        font-weight: lighter;
                                    }
                                    .percent {
                                        font: 250% sans-serif;
                                    }
                                </style>
                            </svg>

                            <ul style="list-style: none; padding: 0;">
                                @foreach ($domains as $domain)
                                    <li style="color: #{{ $domain->primarycolour }}; margin-bottom: 1em;">
                                        @if (Auth::user() && $project->user->id == Auth::user()->id) 
                                            <a href="/assessment/{{ $project->slug }}/{{$domain->slug}}">
                                        @endif
                                        <?php
                                            $xlineto = 979.716 * ($domain->percentanswered / 100);
                                            $circlemidpoint = 1000 * (100 / 100) + 151.683; // 1000 * ($domain->percentanswered / 100) + 151.683; // latter code makes circle follow progress bar, decided to fix it at end instead
                                            $textmidpoint = $circlemidpoint - 40;
                                        ?>
                                        <svg width="100%" viewbox="0 0 1180 175">
                                            <g transform="translate(-63.76 -813.783)">
                                                <path d="M166.896 901.706h979.716" style="fill:none;stroke:#b3b3b3;stroke-width:20;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
                                                <path d="M166.896 901.706h{{ $xlineto }}" style="fill:none;stroke:#{{ $domain->primarycolour }};stroke-width:20;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
                                                <circle r="60" cy="901.706" cx="{{ $circlemidpoint }}" style="fill:#fff;stroke:#{{ $domain->primarycolour }};stroke-width:10;stroke-linecap:round;stroke-miterlimit:4;stroke-dasharray:none"/>
                                                <text y="915" x="{{ $textmidpoint + 40 }}" fill="#{{ $domain->primarycolour }}" text-anchor="middle" class="percent">{{ $domain->percentanswered }}%</text>
                                                <circle r="60" cy="901.706" cx="151.683" style="fill:#fff;stroke:#{{ $domain->primarycolour }};stroke-width:10;stroke-linecap:round;stroke-miterlimit:4;stroke-dasharray:none"/>
                                            </g>
                                            <text x="165" y="30" fill="#{{ $domain->primarycolour }}" dominant-baseline="middle" text-anchor="left" class="name">{{ $domain->name }}</text>
                                            <text x="1000" y="32" fill="#{{ $domain->primarycolour }}" dominant-baseline="middle" text-anchor="end" class="fraction">({{ $domain->questionsanswered }}/{{ $domain->domain_questions_count }})</text>
                                        </svg>
                                        @if (Auth::user() && $project->user->id == Auth::user()->id) 
                                            </a>
                                        @endif
                                    </li>
                                
                                @endforeach
                            </ul>

                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection