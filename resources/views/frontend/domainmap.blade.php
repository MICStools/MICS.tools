@extends('layouts.frontend')

@section('content')

<div class="container">
    <div class="row" style="position: relative;">
    
        <div id="mapprogress">
            <div class="row">
                <div id="logo">
                    <a href="/projects/{{ $project->slug }}" id="projectlogo" style="background-image: url({{ $project->logo ? $project->logo->getUrl() : '/css/defaultlogo.png' }});">&nbsp;</a>
                    <a href="/projects/{{ $project->slug }}"><h4>{{ $project->shortname }}</h4></a>
                </div>
                <ul>
                    @foreach ($domains as $domain)
                        <?php
                            $dasharray = 1000 * ($domain->percentanswered / 100);
                        ?>
                        <a href="/assessment/{{ $project->slug }}/{{ $domain->slug }}">
                            <svg height="100" viewbox="0 0 850 600">
                                <style>
                                    .large { 
                                        font: 100px sans-serif; 
                                    }
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
                    @endforeach
                </ul>
            </div>
        </div><!-- mapprogress -->

        <object id="domainmapsvg" type="image/svg+xml" style="width: 100%; margin: 0;" data="/domain_map.svg"></object>
    </div>
</div>
@endsection