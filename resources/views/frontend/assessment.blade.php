@extends('layouts.frontend')

@section('content')
<div class="container">

    <div id="scoreboard">
        <a href="/projects/{{ $project->slug }}" id="projectlogo" style="background-image: url({{ $project->logo ? $project->logo->getUrl() : '/css/defaultlogo.png' }});"></a>
        <a href="/projects/{{ $project->slug }}"><h4>{{ $project->shortname }}</h4></a>
        <h3>{{ $currentdomain->name }}</h3>
        @foreach ($domains as $domain)
            @if ($domain->id == $currentdomain->id)
                <div class="totalprogress">
                    <p>
                        <span id="questionsanswered">{{ $questionsanswered }}</span> of <span id="totalquestions">{{ $currentdomain->domainQuestions->count() }}</span> questions completed!
                    </p>
                    <?php
                        $dasharray = 1000 * ($domain->percentanswered / 100);
                    ?>
                    <svg height="160" viewbox="0 0 500 500">
                        <style>
                            .large { 
                                font: 100px sans-serif; 
                            }
                        </style>
                        <g id="currentprogress" class="circle circle-1">
                        <circle cx="250" cy="250" fill="transparent" r="159.155" stroke="#ccc" stroke-width="60"></circle>
                        <circle id="currentprogresscircle" transform="rotate(-90, 250, 250)" cx="250" cy="250" fill="transparent" r="159.155" stroke="{{ $domain->colour }}" stroke-dasharray="{{ $dasharray }}, {{ 1000-$dasharray }}" stroke-dashoffset="0" stroke-width="60"></circle>
                        <text x="50%" y="254" fill="{{ $domain->colour }}" dominant-baseline="middle" text-anchor="middle" class="large"><tspan id='currentprogresstspan'>{{ $domain->percentanswered }}</tspan>%</text>
                        </g>
                    </svg>
                </div>
            @endif
        @endforeach
        <div class="row">
            <ul>
            @foreach ($domains as $domain)
                @if ($domain->id <> $currentdomain->id)
                    <?php
                        $dasharray = 1000 * ($domain->percentanswered / 100);
                    ?>
                    <a href="/assessment/{{ $project->slug }}/{{ $domain->slug }}">
                        <svg height="100" viewbox="0 0 850 600">
                            <style>
                                .label { 
                                    font: 75px sans-serif; 
                                }
                            </style>
                            <g id="currentprogress" class="circle circle-1">
                            <circle cx="50%" cy="250" fill="transparent" r="159.155" stroke="#ccc" stroke-width="60"></circle>
                            <circle transform="rotate(-90, 424, 250)" cx="50%" cy="250" fill="transparent" r="159.155" stroke="{{ $domain->colour }}" stroke-dasharray="{{ $dasharray }}, {{ 1000-$dasharray }}" stroke-dashoffset="0" stroke-width="60"></circle>
                            <text x="50%" y="254" fill="{{ $domain->colour }}" dominant-baseline="middle" text-anchor="middle" class="large"><tspan>{{ $domain->percentanswered }}</tspan>%</text>
                            <text x="50%" y="495" fill="{{ $domain->colour }}" dominant-baseline="middle" text-anchor="middle" class="label">{{ $domain->name }}</text>
                            </g>
                        </svg>
                    </a>
                @endif
            @endforeach
            </ul>
        </div>
    </div> <!-- scoreboard -->

    <div id="dotpathdiv">
        <object id="dotpath" type="image/svg+xml" data="{{ $currentdomain->background ? $currentdomain->background->getUrl() : '/defaultbackground.svg' }}"></object>
    </div>
        

        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
        <script>
            window.addEventListener('load', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                dotpath = document.getElementById('dotpath');
                path = dotpath.contentDocument.getElementById('dotpathpath');

                layer1 = dotpath.contentDocument.getElementById('Question_Path');

                dots = {{ $currentdomain->domainQuestions->count() }};
                totallength = path.getTotalLength();
                spacebetweendots = (totallength / (dots+1));

                gsap.set(path, {
                    
                    strokeDasharray: totallength,
                    strokeDashoffset: totallength
                });

                // unhide the SVG (hidden to stop FOUC)
                dotpath.style.opacity = 1;

                // Animate the line in
                gsap.to(path, {
                    duration: 5,
                    strokeDashoffset: 0,
                    delay: 1
                });

                // Put dotstatus from blade into js array
                dotstatus = [];
                @foreach($dotstatus as $status)
                    dotstatus[{{ $loop->iteration-1 }}] = {{ $status }};
                @endforeach

                // Create the dots
                for (var i = 0; i < dots; i++) {
                    point = path.getPointAtLength(spacebetweendots * (i+1));
                    var circle = document.createElementNS("http://www.w3.org/2000/svg", 'circle');
                    circle.setAttributeNS(null, 'cx', point.x);
                    circle.setAttributeNS(null, 'cy', point.y);
                    circle.setAttributeNS(null, 'r', 30);
                    circle.setAttributeNS(null, 'class', 'dot');
                    circle.setAttributeNS(null, 'id', 'dot-'+i);

                    // And the dot number labels
                    var label = document.createElementNS("http://www.w3.org/2000/svg", 'text');
                    label.setAttributeNS(null, 'x', point.x-1);
                    label.setAttributeNS(null, 'y', point.y+2.5);
                    label.setAttributeNS(null, 'dominant-baseline', "middle");
                    label.setAttributeNS(null, 'text-anchor', "middle");
                    label.setAttributeNS(null, 'style', 'fill: #000; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;');
                    label.setAttributeNS(null, 'class', 'dotlabel');
                    label.textContent = i+1;

                    // check for answers
                    answerarray = [];

                    // Get all children inputs of the question
                    questionid = 'question-'+(i+1);           
                    document.getElementById(questionid).querySelectorAll('input').forEach(
                        element => {if (element.checked) answerarray.push(element.value)}
                    );

                    // set colour based on if answered or not, or blocked
                    switch (dotstatus[i]) {
                            case 1: // answered
                                console.log('dotstatus: #'+(i+1)+' = answered');
                                circle.setAttributeNS(null, 'style', 'fill: #{{ $currentdomain->primarycolour }}; stroke: #{{ $currentdomain->primarycolour }}; stroke-width: 5px; cursor: pointer;' );
                                label.setAttributeNS(null, 'style', 'fill: #ededed; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;' );
                                break;
                            case -1: // blocked
                                console.log('dotstatus: #'+(i+1)+' = blocked');
                                circle.setAttributeNS(null, 'style', 'fill: #{{ $currentdomain->primarycolour }}; stroke: #9F2A2A; stroke-width: 5px; cursor: not-allowed;' );
                                label.setAttributeNS(null, 'style', 'fill: #999; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;' );
                                break;
                            default: // 0 = unanswered
                                console.log('dotstatus: #'+(i+1)+' = unanswered');
                                circle.setAttributeNS(null, 'style', 'fill: #ededed; stroke: #{{ $currentdomain->primarycolour }}; stroke-width: 5px; cursor: pointer;' );
                                label.setAttributeNS(null, 'style', 'fill: #000; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;' );
                    } 
                        
                    

                    // Append the dots and the labels
                    layer1.appendChild(circle);
                    layer1.appendChild(label);
                }

                // animate the dots in
                gsap.from(dotpath.contentDocument.querySelectorAll('.dot'), {
                    duration: 1.5,
                    delay: 3,
                    opacity: 0, 
                    y: -200, 
                    stagger: 0.2,
                    ease: "expo"
                });

                // animate the dot labels in
                gsap.from(dotpath.contentDocument.querySelectorAll('.dotlabel'), {
                    duration: 1.5,
                    delay: 3,
                    opacity: 0, 
                    y: -200, 
                    stagger: 0.2,
                    ease: "expo"
                });

                // add click events to dots
                dotpath.contentDocument.querySelectorAll('circle').forEach(item => {
                    item.addEventListener('click', (e) => {
                        questionnumber = Number(e.target.id.split('-')[1]); // zero indexed
                        questionid = 'question-'+(questionnumber+1); // id of html element
                        console.log('Qustion Id: '+questionid);
                        hideallquestions();
                        if (dotstatus[questionnumber] == -1) {
                            console.log('Question blocked');
                        } else {
                            document.getElementById(questionid).style.visibility = 'visible';
                        }
                    })
                })

            }, false);

            function hideallquestions() {
                console.log('hideallquestions');
                document.querySelectorAll('.question').forEach(question => {
                    question.style.visibility = 'hidden';
                }); 
            }

            function showpreviousquestion(currentid) {
                console.log('showpreviousquestion ' + currentid);
                previousid = currentid -1;
                found = false;
                while (previousid >= 0 && !found) {
                    if (dotstatus[previousid-1] == -1) { // correct for zero index
                        // blocked, try the previous one
                        console.log('skipping ' + previousid);
                        previousid--;
                    } else {
                        // answered or unanswered, so show the question
                        previousquestionid = 'question-' + previousid;
                        console.log('Found '+ previousid);
                        document.getElementById(previousquestionid).style.visibility = 'visible';
                        found = true;
                    }
                } // falls off the beginning without showing any questions if all previous ones are blocked, which is OK behaviour 
            }

            function shownextquestion(currentid) {
                console.log('shownextquestion ' + currentid);
                nextid = currentid +1;
                found = false;
                while (nextid < dotstatus.length && !found) {
                    if (dotstatus[nextid-1] == -1) { // correct for zero index
                        // blocked, try the next one
                        console.log('skipping ' + nextid);
                        nextid++;
                    } else {
                        // answered or unanswered, so show the question
                        nextquestionid = 'question-' + nextid;
                        console.log('Found '+ nextid);
                        document.getElementById(nextquestionid).style.visibility = 'visible';
                        found = true;
                    }
                } // falls off the end without showing any questions if all following ones are blocked, which is OK behaviour
            }

            function backtomap() {
                window.location.href = window.location + "/..";
            }

            function answerdot(dotid, questionid) {
               
                answerarray = [];

                // Get all children inputs of the question                
                document.getElementById('question-'+(Number(dotid))).querySelectorAll('input').forEach(
                    element => {if (element.checked) answerarray.push(element.value)}
                );

                console.log('answerarray: '+answerarray);

                ajaxurl = "/assessment/{{ $project->slug }}/answer/"+questionid;
                console.log('ajaxurl: '+ajaxurl);

                $.ajax({
                    type:'POST',
                    url:ajaxurl,
                    data:{projectid:{{ $project->id }}, questionid:questionid, answerarray:answerarray},
                    success:function(data){
                        // Update right hand side info
                        document.getElementById('questionsanswered').textContent = data['questionsanswered'];
                        numberquestionsanswered = Number(data['questionsanswered']);
                        numbertotalquestions = Number({{ $currentdomain->domainQuestions->count() }});
                        percentcomplete = Math.round(100 * numberquestionsanswered / numbertotalquestions);
                        document.getElementById('currentprogresstspan').textContent = percentcomplete;
                        strokeDasharray = "" + (1000 * (percentcomplete/100)) + "," + (1000 * (1-(percentcomplete/100)));
                        console.log('strokeDasharray', strokeDasharray);
                        document.getElementById('currentprogresscircle').setAttributeNS(null, 'stroke-dasharray', strokeDasharray);

                        // Go through each dot and update based on dotstatus
                        dotpath = document.getElementById('dotpath');
                        alldots = dotpath.contentDocument.getElementsByClassName('dot');
                        for (let i=0; i < alldots.length; i++) {
                            dot = alldots[i];
                            label = dot.nextElementSibling;
                            dotstatus[i] = data['dotstatus'][i];
                            switch (dotstatus[i]) {
                                case 1: // answered
                                    console.log('dotstatus: #'+(i+1)+' = answered');
                                    dot.setAttributeNS(null, 'style', 'fill: #{{ $currentdomain->primarycolour }}; stroke: #{{ $currentdomain->primarycolour }}; stroke-width: 5px; cursor: pointer;' );
                                    label.setAttributeNS(null, 'style', 'fill: #ededed; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;' );
                                    break;
                                case -1: // blocked
                                    console.log('dotstatus: #'+(i+1)+' = blocked');
                                    dot.setAttributeNS(null, 'style', 'fill: #{{ $currentdomain->primarycolour }}; stroke: #9F2A2A; stroke-width: 5px; cursor: not-allowed;' );
                                    label.setAttributeNS(null, 'style', 'fill: #999; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;' );
                                    break;
                                default: // 0 = unanswered
                                    console.log('dotstatus: #'+(i+1)+' = unanswered');
                                    dot.setAttributeNS(null, 'style', 'fill: #ededed; stroke: #{{ $currentdomain->primarycolour }}; stroke-width: 5px; cursor: pointer;' );
                                    label.setAttributeNS(null, 'style', 'fill: #000; font-size: 36px; font-family: Halcom-Medium; pointer-events: none;' );
                            }
                        }
                    }
                });

            }

        </script>

                <div><!-- questions -->
                    <ol class="questions">
                
                        @foreach($currentdomain->domainQuestions as $key => $question)
                            <li class="question" id="question-{{ $loop->iteration }}">
                                <div class="questionbox domain-{{ $currentdomain->slug }}">
                                    <h4 class="text-center">{{ $currentdomain->name }}: Question {{ $loop->iteration }}</h4>

                                    <div class="questiontext">{!! $question->text !!}</div>

                                    <ul class="answers">
                                        @foreach($question->questionAnswers as $key => $answer)
                                            <li>
                                                @switch($question->type)
                                                    @case('checkboxes')
                                                        <input type="checkbox" id="checkboxanswer-{{$question->id}}-{{$answer->id}}" name="checkboxset-{{$question->id}}" value="{{$answer->id}}" {{ (in_array($answer->id, old('answers', [])) || $project->projectsAnswers->contains($answer->id)) ? 'checked' : '' }}>
                                                        <label for="checkboxanswer-{{$question->id}}-{{$answer->id}}">{!! $answer->text !!}</label>
                                                        @break

                                                    @case('radiobuttons')
                                                        <input type="radio" id="radioanswer-{{$question->id}}-{{$answer->id}}" name="radioset-{{$question->id}}" value="{{$answer->id}}" {{ (in_array($answer->id, old('answers', [])) || $project->projectsAnswers->contains($answer->id)) ? 'checked' : '' }}>
                                                        <label for="radioanswer-{{$question->id}}-{{$answer->id}}">{!! $answer->text !!}</label>
                                                        @break

                                                    @case('likert')
                                                        <input type="radio" id="likertanswer-{{$question->id}}-{{$answer->id}}" name="likertset-{{$question->id}}" value="{{$answer->id}}" {{ (in_array($answer->id, old('answers', [])) || $project->projectsAnswers->contains($answer->id)) ? 'checked' : '' }}>
                                                        <label for="likertanswer-{{$question->id}}-{{$answer->id}}">{!! $answer->text !!}</label>
                                                        @break

                                                    @default
                                                        <span>default</span> - {!! $answer->text !!}
                                                @endswitch
                                            </li>
                                        @endforeach
                                    </ul>
                                    
                                    <div class="submitbuttons">
                                        @if (false == $loop->first) <a href="javascript:void(0);" class="previousquestion" onclick="hideallquestions(); answerdot({{ $loop->iteration }}, {{$question->id}}); showpreviousquestion({{ $loop->iteration }});"><i class="fa fa-chevron-left"></i></a>@endif
                                        <a href="javascript:void(0);" class="backtoroute" onclick="hideallquestions(); answerdot({{ $loop->iteration }}, {{$question->id}});"><i class="fa fa-times"></i></a>
                                        @if ($loop->last) 
                                            <a href="javascript:void(0);" class="return-to-map" onclick="hideallquestions(); answerdot({{ $loop->iteration }}, {{$question->id}}); backtomap();"><i class="fa fa-map-o" aria-hidden="true"></i></a>
                                        @else
                                            <a href="javascript:void(0);" class="nextquestion" onclick="hideallquestions(); answerdot({{ $loop->iteration }}, {{$question->id}}); shownextquestion({{ $loop->iteration }});"><i class="fa fa-chevron-right"></i></a>
                                        @endif
                                    </div>

                                    <ul class="helpinfo" style="background: url('/{{ $currentdomain->slug }}TL.svg'), url('/{{ $currentdomain->slug }}TR.svg'), url('/{{ $currentdomain->slug }}BL.svg'), url('/{{ $currentdomain->slug }}BR.svg'); background-repeat: no-repeat, no-repeat, no-repeat, no-repeat; background-position: top left, top right, bottom left, bottom right; background-size: 15%, 15%, 15%, 15%;">
                                        @if ($question->help)
                                            <li class="helpbutton"><a href="javascript:void(0);" onclick="document.getElementById('help-{{$loop->iteration}}').classList.toggle('hiddenbox');"><i class="fa fa-question" title="Help"></i></a></li>    
                                        @endif
                                        @if ($question->information)
                                            <li class="infobutton"><a href="javascript:void(0);" onclick="document.getElementById('info-{{$loop->iteration}}').classList.toggle('hiddenbox');"><i class="fa fa-info" title="Info"></i></a></li>
                                        @endif
                                    </ul>

                                    
                                </div>
                                <div class="helpbox hiddenbox" id="help-{{ $loop->iteration }}">
                                    {!! $question->help !!} 
                                    <div class="closebutton">
                                        <a href="javascript:void(0);" onclick="document.getElementById('help-{{$loop->iteration}}').classList.toggle('hiddenbox');"><i class="fa fa-times"></i></a>
                                    </div>
                                </div>

                                <div class="infobox hiddenbox" id="info-{{ $loop->iteration }}">
                                    {!! $question->information !!}
                                    <div class="closebutton">
                                        <a href="javascript:void(0);" onclick="document.getElementById('info-{{$loop->iteration}}').classList.toggle('hiddenbox');"><i class="fa fa-times"></i></a>
                                    </div>
                                </div>

                            </li>
                        @endforeach
                    </ol>           
                </div><!-- questions -->

            <div id="returnnav">
                <ul class="p-0 m-0 d-flex flex-row justify-content-between">
                    <li><a class="how-to-play" href="https://about.mics.tools/how-to" target="_blank" rel="help"><i class="fa fa-question-circle" aria-hidden="true"></i> How-to guide</a></li>
                    <li><a class="return-to-map" href="/assessment/{{ $project->slug }}"><i class="fa fa-map-o" aria-hidden="true"></i> Go to domain map</a></li>
                    <li><a class="return-to-project" href="/projects/{{ $project->slug }}"><i class="fa fa-undo" aria-hidden="true"></i> Return to project page</a></li>
                </ul>
            </div><!-- returnnav -->
        
</div><!-- container -->
@endsection