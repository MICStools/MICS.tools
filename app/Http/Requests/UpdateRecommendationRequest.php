<?php

namespace App\Http\Requests;

use App\Models\Recommendation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRecommendationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('recommendation_edit');
    }

    public function rules()
    {
        return [
            'domain_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'required',
                'unique:recommendations,title,' . request()->route('recommendation')->id,
            ],
            'text' => [
                'required',
            ],
            'questions.*' => [
                'integer',
            ],
            'questions' => [
                'array',
            ],
            'minscore' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'maxscore' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
