<?php

namespace App\Http\Controllers;

use App\City;
use Auth;

use Illuminate\Http\Request;
use Input;
use Validator;

class CityController extends Controller
{

    /**
    * Get all City.
    *
    * @return JSON
    */
    public function index()
    {
        $cities = City::all();

        return response()->success(compact('cities'));
    }


   


    /**
    * Get City details referenced by id.
    *
    * @param int City ID
    *
    * @return JSON
    */
    public function show($id)
    {   

        $cities = City::find($id);

        return response()->success($cities);
    }


     /**
    * Get City details referenced by id.
    *
    * @param int City ID Province
    *
    * @return JSON
    */
    public function byProvince($id)
    {   
        
        $cities = City::where("province_id","=",$id)->get();

        return response()->success($cities);
    }

}
