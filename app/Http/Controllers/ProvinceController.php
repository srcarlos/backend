<?php

namespace App\Http\Controllers;

use App\Province;
use Auth;

use Illuminate\Http\Request;
use Input;
use Validator;

class ProvinceController extends Controller
{

    /**
    * Get all Province.
    *
    * @return JSON
    */
    public function index()
    {
      
        $provincies = Province::all();
        
        return response()->success(compact('provincies'));
    }


   
    /**
    * Get Province details referenced by id.
    *
    * @param int Province ID
    *
    * @return JSON
    */
    public function show($id)
    {   

        $provincies = Province::find($id);

        return response()->success($provincies);
    }

   
    /**
    * Get Province details referenced by id.
    *
    * @param int Province ID Country
    *
    * @return JSON
    */
    public function byCountry($id)
    {   
        
        $provincies = Province::where("country_id","=",$id)->get();

        return response()->success($provincies);
    }

}
