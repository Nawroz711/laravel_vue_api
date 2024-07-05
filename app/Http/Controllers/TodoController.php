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
        $data = ModelsTodo::paginate(5);

        return response()->json($data);
    }

    public function create(Todo $request)
    {
        $data = $request->all();

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
}
