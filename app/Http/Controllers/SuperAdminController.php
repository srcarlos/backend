<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Bican\Roles\Models\Role;

class SuperAdminController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            "name" => "required|min:4",
            "email" => "required|email|unique:user,email",
            "password" => "required|min:4|confirmed"
        ]);

        $user = User::create($request->all());

        //$role = Role::find(1);

        $user->attachRole(1);

        return response()->success('Super Usuario creado exitosamente');
    }
}
