<?php

namespace App\Http\Controllers;

use App\Country;
use Auth;

use Illuminate\Http\Request;
use Input;
use Validator;

class CountryController extends Controller
{

    /**
    * Get all Country.
    *
    * @return JSON
    */
    public function index()
    {
        $countries = Country::all();

        return response()->success(compact('countries'));
    }


   


    /**
    * Get Country details referenced by id.
    *
    * @param int Country ID
    *
    * @return JSON
    */
    public function show($id)
    {   

        $countries = Country::find($id);

        return response()->success($countries);
    }

   



}
