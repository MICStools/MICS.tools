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
        return [
            'name' => [
                'string',
                'required',
                'unique:projects,name,' . request()->route('project')->slug,
            ],
            'shortname' => [
                'string',
                'required',
                'unique:projects,shortname,' . request()->route('project')->slug,
            ],
            'slug' => [
                'string',
                'required',
                'unique:projects,slug,' . request()->route('project')->slug,
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
