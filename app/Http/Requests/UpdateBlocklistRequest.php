<?php

namespace App\Http\Requests;

use App\Models\Blocklist;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBlocklistRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('blocklist_edit');
    }

    public function rules()
    {
        return [
            'questions.*' => [
                'integer',
            ],
            'questions' => [
                'required',
                'array',
            ],
            'answers.*' => [
                'integer',
            ],
            'answers' => [
                'required',
                'array',
            ],
        ];
    }
}
