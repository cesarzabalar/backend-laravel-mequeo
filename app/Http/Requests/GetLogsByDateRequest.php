<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLogsByDateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get date from request route
     *
     * @return array
     */
    public function all($keys = null): Array
    {
        $data = parent::all($keys);
        $data['date'] = $this->route('date');
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date|date_format:Y-m-d H:i:s',
        ];
    }

}
