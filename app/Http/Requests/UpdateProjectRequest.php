<?php

namespace App\Http\Requests;

use App\Models\Project;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('project_edit');
    }

    public function rules()
    {
        //ddd(request());
        return [
            'slug' => [
                'string',
                'required',
                'unique:projects,slug,' . request()->route('project')->id,
            ],

            'name' => [
                'string',
                'required',
                'unique:projects,name,' . request()->route('project')->id,
            ],
            'shortname' => [
                'string',
                'required',
                'unique:projects,shortname,' . request()->route('project')->id, // request()->route('projects') . ",slug", 
            ],
            
            'user_id' => [
                'required',
                'integer',
            ],
            'startdate' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'enddate' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'contact' => [
                'string',
                'nullable',
            ],
            'contactdetails' => [
                'string',
                'nullable',
            ],
            'uri' => [
                'string',
                'nullable',
            ],
            'organisers.*' => [
                'integer',
            ],
            'organisers' => [
                'array',
            ],
        ];
    }
}
