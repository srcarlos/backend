<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests;
use Validator;

class CategoryController extends Controller
{

    /**
    * Get all categories.
    *
    * @return JSON
    */
    public function index()
    {
        $categories = Category::where("id",">=",1)->get();

        return response()->success(compact('categories'));
    }


    /**
    * Create new category.
    *
    * @return JSON
    */
    public function store(Request $request)
    {   
        $data = $request->all();


        $category = Category::create($data);

        return response()->success(compact('category'));
    }


    /**
    * Get Category details referenced by id.
    *
    * @param int Category ID
    *
    * @return JSON
    */
    public function show($id)
    {   

        $category = Category::find($id);

        return response()->success($category);
    }

    /**
    * Update Category data.
    *
    * @return JSON success message
    */
    public function update($id, Request $request)
    {   



        $category = Category::find($id);

        $validator = $this->validator($request->all(),$id);

        if( $validator->fails() ){
            return response()->json(['errors' => $validator->errors()],422);
        }

        $category->name = $request->name;

        $category->save();

        return response()->success('success');
    }

    /**
    * Delete Category Data.
    *
    * @return JSON success message
    */
    public function destroy($id)
    {   
        $category = Category::find($id);
        
        if($category){
            $category->delete();
            return response()->success('success');
        }
       
         return response()->json('No existe',401);
    }


    /**
    *
    *
    *  Validador
    *
    *
    */
    protected function validator(array $data, $id = null){

        if ( $id ) {

            return Validator::make($data, [
                'name' => 'required|string|min:3'
            ]);
        }

        return Validator::make($data, [
            'name' => 'required|string|min:3',
        ]);
    }

}
