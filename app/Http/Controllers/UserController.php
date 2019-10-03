<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use Hash;
use Illuminate\Http\Request;
use Input;
use Validator;
use Storage;

class UserController extends Controller
{
    /**
     * Get user current context.
     *
     * @return JSON
     */
    public function getMe()
    {
        $user = Auth::user();
        
        $allData = User::with("compania")->where("id",$user->id)->first();
        return response()->success($allData);
    }

    /**
     * Update user current context.
     *
     * @return JSON success message
     */
    public function putMe(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            "data.nombre" => "required",
            "data.apellido" => "required",
            "data.telefono" => "required",
            'data.email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $userForm = app('request')
                    ->only(
                        'data.current_password',
                        'data.new_password',
                        'data.new_password_confirmation',
                        'data.nombre',
                        'data.email'
                    );

        $userForm = $userForm['data'];
        $user->nombre = $userForm['nombre'];
        $user->email = $userForm['email'];
        
       if(isset($userForm['apellido'])) $user->apellido = $userForm['apellido'];
       if(isset($userForm['telefono'])) $user->telefono = $userForm['telefono'];
       if(isset($userForm['compania_id'])) $user->compania_id = $userForm['compania_id'];
       
        if ($request->has('data.current_password')) {
            Validator::extend('hashmatch', function ($attribute, $value, $parameters) {
                return Hash::check($value, Auth::user()->password);
            });

            $rules = [
                'data.current_password' => 'required|hashmatch:data.current_password',
                'data.new_password' => 'required|min:8|confirmed',
                'data.new_password_confirmation' => 'required|min:8',
            ];

            $payload = app('request')->only('data.current_password', 'data.new_password', 'data.new_password_confirmation');

            $messages = [
                'hashmatch' => 'Invalid Password',
            ];

            $validator = app('validator')->make($payload, $rules, $messages);

            if ($validator->fails()) {
                return response()->error($validator->errors());
            } else {
                $user->password = Hash::make($userForm['new_password']);
            }
        }

        $user->save();

        return response()->success('success');
    }

    /**
     * Get all users.
     *
     * @return JSON
     */
    public function getIndex(Request $request)
    {
        $users = User::with("compania");
        $recordsTotal = User::all()->count();
        $settings = json_decode($request->settings, TRUE);
        $draw = $settings['draw'];
        $length = $settings['length'];
        $start = $settings['start'];
        $search = $settings['search']['value'];
        $orderCol = $settings['order'][0]['column'];
        $orderDir = $settings['order'][0]['dir'];
        $columns = $settings['columns'];

        $columnNames = [
            'id' => 'id',
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'email' => 'email',
            'telefono' => 'telefono'
        ];

        if( !empty(trim($search)) ) {
            $users->where('id','like', '%'.$search.'%')
                ->orwhere('nombre','like', '%'.$search.'%')
                ->orwhere('apellido','like', '%'.$search.'%')
                ->orwhere('email','like', '%'.$search.'%')
                ->orwhere('telefono','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $users->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $users->count();

        if( intval($start) > 0 ) {
            $users->skip($start);
        }
        if( intval($length) > 0 ) {
            $users->take($length);
        }

        $users = $users->get();
        $users = $users->toArray();

        $response['users'] = $users;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }

    /**
     * Get user details referenced by id.
     *
     * @param int User ID
     *
     * @return JSON
     */
    public function getShow($id)
    {
        $user = User::find($id);
        $user['role'] = $user
                        ->roles()
                        ->select(['slug', 'roles.id', 'roles.name'])
                         
                        ->get();

        return response()->success($user);
    }

    /**
     * add user.
     *
     * @return JSON success message
     */
   
        public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'       => 'required|min:3',
            'apellido'       => 'required',
            'telefono'       => 'required|unique:users',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required',
        ]);

        $user = new User();
        $user->nombre = trim($request->nombre);
        $user->telefono = $request->telefono;
        $user->apellido = trim($request->apellido);
        $user->email = trim(strtolower($request->email));
        $user->password = bcrypt($request->password);
        $user->email_verified =1;

        // Validacion de usuario por compania
        if(isset($request->compania_id)){
            $user->compania_id = $request->compania_id;
           /* $result = User::where("compania_id","=",$request->compania_id)->first();
            if(count($result)){
             $error =  ['compania' => ["Ya existe un usuario a esa compania"]];
             return response()->json(['errors' =>  $error ],422);   
            }else {
             $user->compania_id = $request->compania_id;
           }*/
        } 


       $user->save();
       foreach (Input::get('role') as $setRole) {
            $user->attachRole($setRole);
        }
        return response()->success(compact('user'));
    }

    /**
     * Update user data.
     *
     * @return JSON success message
     */
    public function putShow(Request $request)
    {
        $userForm = array_dot(
            app('request')->only(
                'data.nombre',
                'data.email',
                'data.id',
                'data.apellido',
                'data.telefono',
                'data.compania_id'
            ) 
        );

        $userId = intval($userForm['data.id']);

        $user = User::find($userId);

        $this->validate($request, [

            'data.id' => 'required|integer',
            'data.nombre' => 'required|min:3',
            'data.apellido' => 'required',
            'data.telefono' => 'required',
            'data.email' => 'required|email|unique:users,email,'.$user->id,
            //'data.compania_id' => 'required',
        ]);
       
        $userData = [
            'nombre' => $userForm['data.nombre'],
            'apellido' => $userForm['data.apellido'],
            'email' => $userForm['data.email'],
            'telefono' => $userForm['data.telefono'],

        ];

        if(isset($userForm['data.compania_id']) &&  $user->compania_id != $userForm['data.compania_id']){
             $userData["compania_id"] = $userForm['data.compania_id'];
         /*   $result = User::where("compania_id","=",$userForm['data.compania_id'])->first();
            if(count($result)){
             $error =  ['compania' => ["Ya existe un usuario a esa compania"]];
             return response()->json(['errors' =>  $error ],422);   
            }else {   
              $userData["compania_id"] = $userForm['data.compania_id'];
           }*/

        } 
        $affectedRows = User::where('id', '=', $userId)->update($userData);

        $user->detachAllRoles();

        foreach (Input::get('data.role') as $setRole) {
            $user->attachRole($setRole);
        }

        return response()->success('success');
    }

    /**
     * Delete User Data.
     *
     * @return JSON success message
     */
    public function deleteUser($id)
    {  

       $user = User::find($id);
        $user->delete();
        return response()->success('success');
    }

    /**
     * Get all user roles.
     *
     * @return JSON
     */
    public function getRoles()
    {
        $roles = Role::all();

        return response()->success(compact('roles'));
    }

    /**
     * Get role details referenced by id.
     *
     * @param int Role ID
     *
     * @return JSON
     */
    public function getRolesShow($id)
    {
        $role = Role::find($id);

        $role['permissions'] = $role
                        ->permissions()
                        ->select(['permissions.name', 'permissions.id'])
                        ->get();

        return response()->success($role);
    }

    /**
     * Update role data and assign permission.
     *
     * @return JSON success message
     */
    public function putRolesShow()
    {
        $roleForm = Input::get('data');
        $roleData = [
            'name' => $roleForm['name'],
            'slug' => $roleForm['slug'],
            'description' => $roleForm['description'],
        ];

        $roleForm['slug'] = str_slug($roleForm['slug'], '.');
        $affectedRows = Role::where('id', '=', intval($roleForm['id']))->update($roleData);
        $role = Role::find($roleForm['id']);

        $role->detachAllPermissions();

        foreach (Input::get('data.permissions') as $setPermission) {
            $role->attachPermission($setPermission);
        }

        return response()->success('success');
    }

    /**
     * Create new user role.
     *
     * @return JSON
     */
    public function postRoles(Request $request)
    {   


        $this->validate($request, [
            'role' => 'required',
            'slug' => 'required',
            'permissions' => 'required',
        ]);


        $role = Role::create([
            'name' => Input::get('role'),
            'slug' => str_slug(Input::get('slug'), '.'),
            'description' => Input::get('description'),
        ]);

        foreach (Input::get('permissions') as $setPermission) {
            $role->attachPermission($setPermission);
        }

        return response()->success(compact('role'));
    }

    /**
     * Delete user role referenced by id.
     *
     * @param int Role ID
     *
     * @return JSON
     */
    public function deleteRoles($id)
    {
        Role::destroy($id);

        return response()->success('success');
    }

    /**
     * Get all system permissions.
     *
     * @return JSON
     */
    public function getPermissions()
    {
        $permissions = Permission::all();

        return response()->success(compact('permissions'));
    }

    /**
     * Create new system permission.
     *
     * @return JSON
     */
    public function postPermissions()
    {
        $permission = Permission::create([
            'name' => Input::get('name'),
            'slug' => str_slug(Input::get('slug'), '.'),
            'description' => Input::get('description'),
        ]);

        return response()->success(compact('permission'));
    }

    /**
     * Get system permission referenced by id.
     *
     * @param int Permission ID
     *
     * @return JSON
     */
    public function getPermissionsShow($id)
    {
        $permission = Permission::find($id);

        return response()->success($permission);
    }

    /**
     * Update system permission.
     *
     * @return JSON
     */
    public function putPermissionsShow()
    {
        $permissionForm = Input::get('data');
        $permissionForm['slug'] = str_slug($permissionForm['slug'], '.');
        $affectedRows = Permission::where('id', '=', intval($permissionForm['id']))->update($permissionForm);

        return response()->success($permissionForm);
    }

    /**
     * Delete system permission referenced by id.
     *
     * @param int Permission ID
     *
     * @return JSON
     */
    public function deletePermissions($id)
    {
        Permission::destroy($id);

        return response()->success('success');
    }

    public function newSuperUser(Request $request)
    {
        $this->validate($request,[
            "nombre" => "required",
            "apellido" => "required",
            "email" => "required|email|unique:users,email",
            "telefono" => "required|unique:users,telefono",
            "password" => "required|min:4|confirmed",
            "acceso_mod_ventas" => "boolean",
            "cantidad_platos" => "required_if:acceso_mod_ventas,0",
            "foto" => "image"
        ]);

        $su = User::create([
            "nombre" => $request->nombre,
            "apellido" => $request->apellido,
            "email" => $request->email,
            "telefono" => $request->telefono,
            "password" => bcrypt($request->password),
            "acceso_mod_ventas" => $request->acceso_mod_ventas,
            "cantidad_platos" => $request->cantidad_platos,
	    "email_verified" => 1
        ]);
	$su->email_verified = 1;
	$su->save();

        if($request->hasFile('foto')){
            $_su = User::find($su->id);
            $_su->foto = $su->id.'.'.$request->file('foto')->extension();
            Storage::put('fotos/'.$_su->id.'.'.$request->file('foto')->extension(),
                file_get_contents($request->file('foto')->getRealPath()));
            $_su->save();
        }

        $su->attachRole(1);

        return response()->success('Super Usuario creado exitosamente');
    }

    public function updateSuperUser(Request $request,$id){

        $su = User::find($id);

        $this->validate($request,[
            "nombre" => "required",
            "apellido" => "required",
            "email" => "required|email|unique:users,email,".$su->id,
            "telefono" => "required|unique:users,telefono,".$su->id,
            "password" => "min:4|confirmed",
            "acceso_mod_ventas" => "boolean",
            "cantidad_platos" => "required_if:acceso_mod_ventas,false",
            //"logo" => "required"
        ]);

        if (!$su){
            return response()->json('El super usuario no existe',404);
        }

        $su->name = $request->name;
        $su->apellido = $request->apellido;
        $su->email = $request->email;
        $su->telefono = $request->telefono;
        $su->acceso_mod_ventas = $request->acceso_mod_ventas;
	$su->email_verified = 1;

        if (!$request->acceso_mod_ventas){
            $su->cantidad_platos = $request->cantidad_platos;
        }
        if ($request->password){
            $su->password = bcrypt($request->password);
        }

        if($request->hasFile('foto')){
            $su->foto = $su->id.'.'.$request->file('foto')->extension();
            Storage::delete('fotos/'.$su->id);
            Storage::put('fotos/'.$su->id.'.'.$request->file('foto')->extension(),
                file_get_contents($request->file('foto')->getRealPath()));
        }

        $su->save();

        return response()->success('Super usuario modificado exitosamente');
    }

}
