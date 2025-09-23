<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HealthReq as ValidateRequest;

use App\Models\HealthDec as HealthModel;

class Healthdec extends Controller
{
    private $_model_health;
    
    public function __construct(HealthModel $health_model)
    {
        $this->_model_health = $health_model;

    }

    public function index()
    {
        return view('health-content');
    }

    public function save(ValidateRequest $req)
    {
        $post = $req->all();

        $post['dateinp'] = today();
        $post['datetimecreated'] = now();

        $invalid_form = false;

        $q_loop = 11;

        for ($a = 1; $a <= $q_loop; $a++) {
            if ($post['q5_'.$a] == 1) {
                $invalid_form = true;
                break;
            }

        }

        $insert = $this->_model_health->create($post);

        if ($insert){
            if ($invalid_form) {
                $msg = "<b>Submission successful!</b> ";

            } else {
                $msg = "<b>";
            }
            
            $response_m = [
                'error' => $invalid_form,
                'message' => $msg,
            ];
            
        } else {
            $response_m = $insert;

        }

        return response()->json($response_m);

    }
}
