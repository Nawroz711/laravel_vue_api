<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo;
use App\Models\todo as ModelsTodo;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{

    // get all the data
    public function index()
    {
        $user_id = auth()->id();
        $data = ModelsTodo::where('user_id' , $user_id)->paginate(6);

        return response()->json($data);
    }

    public function create(Todo $request)
    {
        $user_id = auth()->id();
        $data = $request->all();
        $data['user_id'] = $user_id;

        if ($data['attachments'] !== null) {
            $file_name = time() . '_' . $data['attachments']->getClientOriginalName();

            $file = Storage::put('/files/' . $file_name, 'content');
            $data['attachments'] = $file_name;
        }

        $isAdded = ModelsTodo::create($data);


        if ($isAdded) {
            return response()->json([
                'message' => 'Data added successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'oops! something went wrong!'
            ], 422);
        }
    }

    // download attachments
    public function downloadFile($file)
    {
        return Storage::download('/files/' . $file);
    }

    // search method of todos
    public function search(Request $request)
    {
        $data = $request->all();
        $keyword = $data['keyword'];

        if ($keyword == null || $keyword == '') {
            $result =  ModelsTodo::paginate(6);
            return response()->json($result);
        } else {
            $result =  ModelsTodo::where('title', 'like' , $keyword)->paginate(6);

            if (!$result->isEmpty()) {

                return response()->json($result);
            } else {
                return response()->json([
                    'message' => 'No data fount!'
                ], 404);
            }
        }
    }
    // DELETE RECORDS
    public function  destroy(Request $request) {
        $x = $request->all();

        foreach($x as $item)
        {
            ModelsTodo::where('id' , $item)->delete();
        }

        return response()->json([
            'message' =>  'Records deleted successfully!',
        ], 200);
    }
}
