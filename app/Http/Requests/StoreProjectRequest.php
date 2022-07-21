<?php

namespace App\Http\Requests;

use App\Models\Project;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('project_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:projects',
            ],
            'shortname' => [
                'string',
                'required',
                'unique:projects',
            ],
            'slug' => [
                'string',
                'required',
                'unique:projects',
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
