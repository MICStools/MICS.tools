<?php

namespace App\Http\Requests;

use App\Models\Blocklist;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBlocklistRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('blocklist_create');
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
