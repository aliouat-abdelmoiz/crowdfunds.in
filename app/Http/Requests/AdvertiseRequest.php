<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdvertiseRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if(\Auth::check()) {
			return true;
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'title' => 'required',
			'description' => 'required',
            'categories' => 'required',
            'subcategories' => 'required'
		];

        $total_images = count($this->file('images')) - 1;

        foreach(range(0, $total_images) as $index) {
            $rules['image.' . $index] = 'image';
        }

        return $rules;

	}

}
