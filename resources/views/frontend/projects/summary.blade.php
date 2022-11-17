@extends('layouts.frontend')
@section('content')

<div class="container">
    <div class="row mt-2">
        <div class="col-12" style="padding: 1em; background: var(--mics-navy); color: white; position: relative; overflow: hidden;">
            <h1><img style="width: 1.6ex; display: inline-block;" id="projectlogo" src="{{ $project->logo ? $project->logo->getUrl('thumb') : '/css/defaultlogo.png' }}" alt="" /> {{ $project->shortname }} impact summary</h1>
            <p>This is an impact report of the citizen science project {{ $project->name }}. The scores displayed summarise the results of the assessment process designed by the MICS project. For more information on how they were calculated, visit <a href="/">{{ request()->getSchemeAndHttpHost(); }}</a></p>
            {{-- <span class="betaflag">Beta</span> --}}
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-7 p-0" style="background: #535364; border-right: 10px solid var(--mics-offwhite); height: 480px; overflow: hidden;">
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
        
              // Load Charts and the corechart package.
              google.charts.load('current', {'packages':['geochart']});

              google.charts.setOnLoadCallback(drawRegionsMap);

                function drawRegionsMap() {
                    var data1 = google.visualization.arrayToDataTable([
                        {!! $organisersstring !!}
                    ]);

                    var data2 = google.visualization.arrayToDataTable([
                        {!! $participantsstring !!}
                    ]);

                    var data3 = google.visualization.arrayToDataTable([
                        {!! $observersstring !!}
                    ]);

                    var options = {
                        backgroundColor: '#535364',
                        datalessRegionColor: '#b4b4b7',
                        defaultColor: '#84ade9',
                        region: 'auto'
                    };

                    var chart1 = new google.visualization.GeoChart(document.getElementById('regions_div1'));
                    var chart2 = new google.visualization.GeoChart(document.getElementById('regions_div2'));
                    var chart3 = new google.visualization.GeoChart(document.getElementById('regions_div3'));

                    chart1.draw(data1, options);
                    chart2.draw(data2, options);
                    chart3.draw(data3, options);
                }

            </script>            

            <fieldset id="mapnav" style="position: relative;">

                <input type="radio" id="organisers" name="mapchoice" value="organisers" checked>
                <label for="organisers">Organisers</label>

                <input type="radio" id="participants" name="mapchoice" value="participants">
                <label for="participants">Participants</label>

                <input type="radio" id="observers" name="mapchoice" value="observers">
                <label for="observers">Observations</label>
                <div class="mapregion" id="regions_div3"></div>
                <div class="mapregion" id="regions_div2"></div>
                <div class="mapregion" id="regions_div1"></div>

            </fieldset>

        </div>
        <div class="col-5 p-0" style="background: var(--mics-navy);">
            <h2 style="padding: 1ex 1em; color: white;">Project Information</h2>
            <dl class="outputsummarydl" style="padding: 1ex; background: var(--mics-navy); color: white; ">

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

                @if ($project->cost && 0 < $project->cost)
                    <dt>Project cost:</dt>
                    <dd>{{ $project->cost }}</dd>
                @endif

                @if ($project->funding && 0 < $project->funding)
                    <dt>Funding amount:</dt>
                    <dd>{{ $project->funding }}</dd>
                @endif

                @if ($project->uri)
                    <dt>Project URL:</dt>
                    <dd><a href="{{ $project->uri }}">{{ $project->uri }}</a></dd>
                @endif

                {{-- <dt>Number of participants:</dt>
                <dd>3350</dd>

                <dt>Number of observations:</dt>
                <dd>23844</dd> --}}

                <dt>Impact Assesment progress:</dt>
                <dd>{{ $totalprogress }}% complete</dd>

            </dl>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12" style="padding: 1em; background: var(--mics-navy); color: white; position: relative; overflow: hidden;">
            <h3>Rules-based scores</h3>
            <p>These scores are calculated based on a set of rules written to combine a specific set of impact metrics on the same theme into a single indicator. A higher score means the project is carrying out more activities related to the theme of the indicator and is, therefore, more likely to have a higher positive impact in this area. Rule-based scores are only calculated for specific themes. Overall assessments can be found below in the machine-learning--based scoring. Descriptions and explanations of impact indicators are provided at <a href="https://about.mics.tools/indicators">about.mics.tools/indicators</a> (e.g., the score is low on economic productivity because the project did not include specific aspects related to improving efficiency). Different scores trigger different recommendations presented in the following section. Also, scores are not linked to project objectives; they try to capture a broad range of impacts even if the project does not consider or care about all of them. All scores are out of 42.</p>
        </div>
    </div>

    <div class="row mt-2">
        <table class="outputsummarytable">
            <thead>
                <th colspan="2">Impact Indicators</th>
                <th>Impact score <span>(max 42)</span></th>
                <th>Average score (of projects on platform)</th>
            </thead>
            <tbody>

                @foreach ($domainIndicators as $domain)

                    @if ($domain->recommendations->count() > 0)
                        <tr>
                            <th rowspan="{{ $domain->recommendations->count() }}" class="{{ $domain->slug }}"><span class="verticaltext">{{ ucfirst($domain->slug) }}</span></th>

                        @foreach ($domain->recommendations as $recommendation)
                            
                            @if (false == $loop->first)
                                <tr>
                            @endif
                            <td class="{{ $domain->slug }}">{{ $recommendation->label }}</td>
                            <td class="recommendationscore">{{ $recommendation->score }}</td>
                            <td class="recommendationaverage">{{ $recommendation->average }}</td>
                        </tr>
                            
                        @endforeach
                    @endif
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="row mt-2">
        <div class="col-12" style="padding: 1em; background: var(--mics-navy); color: white; position: relative; overflow: hidden;">
            <h3>Recommendations</h3>
            <p>The following recommendations are determined by the scores the project received in the previous section. The recommendations are based on citizen-science best practice as defined in the current scientific literature and how other projects have taken action to improve their impact in specific areas. Of course, following these recommendations does not guarantee the project will suddenly have a higher impact; it all depends on the specific context of each project, but the might provide helpful inspiration.</p>
        </div>
    </div>
    
    <div class="row mt-2">
        <table class="outputsummarytable">
            <tbody>

                @foreach ($domainRecommendations as $domain)

                    @if ($domain->recommendations->count() > 0)
                        <tr>
                            <th rowspan="{{ $domain->recommendations->count() }}" class="{{ $domain->slug }}"><span class="verticaltext">{{ ucfirst($domain->slug) }}</span></th>

                        @foreach ($domain->recommendations as $recommendation)
                            
                            @if (false == $loop->first)
                                <tr>
                            @endif
                            <td class="{{ $domain->slug }}">{{ $recommendation->label }}</td>
                            <td class="recommendationtext recommendationtext-{{ $recommendation->score }}">{!! $recommendation->text !!}</td>
                        </tr>
                            
                        @endforeach
                    @endif
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="row mt-2">
        <div class="col-12" style="padding: 1em; background: var(--mics-navy); color: white; position: relative; overflow: hidden;">
            <h3>Machine Learning Scores</h3>
            <p>The following scores were calculated using a statistically-driven machine-learning approach, a type of AI that learns to perform a task by analysing patterns in data. This is an experimental approach to citizen-science impact assessment, and the exact reasoning behind the scores is not explainable. The scores represent a best guess of the impact the project is having in each domain. How can you use the score? Well, this platform gives a common framework for impact assessment so you can use the scores: to see how the project&apos;s impact evolves over time; to compare the project with others; to report to funders and participants; or for your organisation&apos;s internal reporting. All scores are out of 42.</p>
        </div>

        <svg width="0" height="0">
            <style>
                .large { 
                    font: 300% sans-serif; 
                    fill: #fff;
                    font-weight: lighter;
                }
                .back {
                    stroke: #999;
                    stroke-width: 10px;
                }
                .legend { 
                    font: 250% sans-serif; 
                    fill: #999;
                }
            </style>
        </svg>

        <div class="col-12 pt-6 mt-4 d-flex justify-content-between">
            

            @foreach ($project->projectResults as $result)

                <svg height="210" viewbox="0 0 600 600">
                
                    <g transform="translate(300,600)">
                        <circle cy="-300" r="290" fill="none" stroke="#ccc" stroke-width="12"/>
                        <circle cy="-300" r="270" fill="none" stroke="#ccc" stroke-dasharray="40, 25" stroke-width="12"/>
                        <text x="0" y="-320" dominant-baseline="middle" text-anchor="middle" class="large back" id="domaintextback">{{ $result->domain->name }}</text>
                        <text x="0" y="-240" dominant-baseline="middle" text-anchor="middle" class="large back" id='domainscoreback'>{{ $result->score }}</text>
                        <circle cy="-300" r="{{ 276 * ($result->score/42) }}" fill="#{{ $result->domain->primarycolour }}" id="chartarea" />
                        <text x="0" y="-320" dominant-baseline="middle" text-anchor="middle" class="large" id="domaintext">{{ $result->domain->name }}</text>
                        <text x="0" y="-240" dominant-baseline="middle" text-anchor="middle" class="large" id='domainscore'>{{ $result->score }}</text>
                        @if($loop->last)
                            <text x="220" y="-15" dominant-baseline="middle" text-anchor="middle" class="legend">max. 42</text>
                        @endif
                    </g>
                </svg>
                
            @endforeach
        </div>
        <div class="col-12 text-center p-2 mt-2">
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
                    @foreach ($results as $result)
                        <circle cx="250" cy="250" fill="transparent" r="159.155" stroke="#{{ $result->domain->primarycolour }}" stroke-dasharray="{{ 1000 * $result->percentage }}, {{ 1000 * (1-$result->percentage) }}" stroke-dashoffset="{{ 250 - (1000 * $totalsofar) }}" stroke-width="60"></circle>
                        @php
                            $totalsofar += $result->percentage;
                            echo '<!-- $result->percentage = ' . $result->percentage . ' -->';
                            echo '<!-- $totalsofar = ' . $totalsofar . ' -->';
                        @endphp                        
                    @endforeach

                  <text x="50%" y="220" dominant-baseline="middle" text-anchor="middle" class="totalscore">Total Score</text>
                  <text x="50%" y="300" dominant-baseline="bottom" text-anchor="middle" class="score"><tspan id='currentscore'>{{ $average }}</tspan>/42</text>
                </g>
            </svg>
        </div>
    </div>



</div>
@endsection