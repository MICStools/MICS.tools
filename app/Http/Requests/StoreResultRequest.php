<?php

namespace App\Http\Requests;

use App\Models\Result;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreResultRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('result_create');
    }

    public function rules()
    {
        return [
            'domain_id' => [
                'required',
                'integer',
            ],
            'project_id' => [
                'required',
                'integer',
            ],
            'score' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
