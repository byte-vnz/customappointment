<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthReq extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regx_alpha = '/^[a-zA-Z .]+$/';
        $regx_num = '/^\+?[0-9\ -]+$/';

        return [
            // 'refno' => ['required', 'alpha_num', 'exists:appmstr,refno'],
            'refno' => ['required', 'alpha_num'],
            'lastname' => ['required', 'string', 'max:100', 'regex:'.$regx_alpha],
            'middlename' => ['nullable', 'string', 'max:100', 'regex:'.$regx_alpha],
            'firstname' => ['required', 'string', 'max:100', 'regex:'.$regx_alpha],
            'country' => ['required', 'string'],
            'gender' => ['required', 'in:M,F'],
            'age' => ['required', 'integer', 'max:99'],
            'cno' => ['required', 'regex:'.$regx_num],
            'work' => ['required', 'string'],
            'addrwork' => ['required', 'string'],
            'addrhome' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'tempread' => ['required', 'numeric'],
            'reason' => ['required', 'string'],

            'q1' => ['required', 'boolean'],
            'q1ans' => ['required_if:q1,1'],
            
            'q2' => ['required', 'boolean'],
            'q2ans' => ['required_if:q2,1'],
            
            'q3' => ['required', 'boolean'],
            'q3ans' => ['nullable', 'string'],
            'q4' => ['required', 'boolean'],
            'q4ans' => ['nullable', 'string'],

            'q5_1' => ['required', 'boolean'],
            'q5_2' => ['required', 'boolean'],
            'q5_3' => ['required', 'boolean'],
            'q5_4' => ['required', 'boolean'],
            'q5_5' => ['required', 'boolean'],
            'q5_6' => ['required', 'boolean'],
            'q5_7' => ['required', 'boolean'],
            'q5_8' => ['required', 'boolean'],
            'q5_9' => ['required', 'boolean'],
            'q5_10' => ['required', 'boolean'],
            'q5_11' => ['required', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'refno' => 'appointment reference number',
            'lastname' => 'last name',
            'firstname' => 'first name',
            'middlename' => 'middle name',
            'country' => 'kabansaan',
            'gender' => 'kasarian',
            'age' => 'edad',
            'cno' => 'matatawagang numero',
            'work' => 'trabaho',
            'addrwork' => 'address ng opisina',
            'addrhome' => 'address ng tahanan',
            'email' => 'emaid address',
            'tempread' => 'temperature reading',
            'reason' => 'sadya ng pagpunta',

            'q1' => 'bumiyahe ka ba palabas ng bansa sa nakaraan 14 na araw',
            'q1ans' => 'kung oo, saan',
            
            'q2' => 'bumiyahe ka ba sa ibang lugar sa metro manila simula ng ecq noong march 17, 2020?
            ',
            'q2ans' => 'kung oo, saan',
            
            'q3' => 'may nakasalamuha ka ba na may lagnat/ ubo/ sipon o namamagang lalamunan sa nakaraang 14 na araw?',
            'q3ans' => 'kung oo, saan',
            
            'q4' => 'may nakasama ka ba sa trabaho o sa bahay na nakumpirmang may covid-19?',
            'q4ans' => 'kung oo, saan',

            'q5_1' => 'lagnat',
            'q5_2' => 'ubo',
            'q5_3' => 'sipon',
            'q5_4' => 'madaling mapagod',
            'q5_5' => 'pananakit ng katawan',
            'q5_6' => 'hirap sa paghinga',
            'q5_7' => 'madalas na pagdumi',
            'q5_8' => 'pamamaga ng lalamunan',
            'q5_9' => 'pananakit ng ulo',
            'q5_10' => 'walang pansala o pang amoy',
            'q5_11' => 'paninikip ng dibdib',
        ];
    }
}
