<?php

namespace App\Http\Controllers;

use App\Exports\TodosExport;
use App\Http\Requests\Todo;
use App\Imports\TodosImport;
use App\Models\todo as ModelsTodo;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;

class TodoController extends Controller
{

    // get all the data
    public function index()
    {
        $user_id = auth()->id();
        $data = ModelsTodo::where('user_id', $user_id)->paginate(5);

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
            $result =  ModelsTodo::where('title', 'like', $keyword)->paginate(6);

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
    public function  destroy(Request $request)
    {
        $x = $request->all();

        foreach ($x as $item) {
            ModelsTodo::where('id', $item)->delete();
        }

        return response()->json([
            'message' =>  'Records deleted successfully!',
        ], 200);
    }

    // FORCE DELETE RECORDS
    public function  forceDelete(Request $request)
    {
        $user_id = auth()->id();
        $x = $request->all();

        if($x != null)
        {
            foreach ($x as $item) {
                $file_name = ModelsTodo::where('id', $item)->pluck('attachments')->first();
    
                // IF RECORD HAS ATTACHMENTS REMOVE IT FROM STORAGE
                if ($file_name != null) {
                    Storage::delete('/files/' . $file_name);
                }
                ModelsTodo::where('id', $item)->forceDelete();
            }

        }
        else {
           
                $file_name = ModelsTodo::where('user_id', $user_id)->pluck('attachments')->first();
    
                // IF RECORD HAS ATTACHMENTS REMOVE IT FROM STORAGE
                if ($file_name != null) {
                    Storage::delete('/files/' . $file_name);
                }
                ModelsTodo::where('user_id', $user_id)->forceDelete();
            
        }

        return response()->json([
            'message' =>  'Records deleted successfully!',
        ], 200);
    }

    // EDIT TODOS
    public function edit($id)
    {

        $data = ModelsTodo::where('id', $id)->get();

        return response()->json($data[0]);
    }

    // UPDATE TODOS
    public function update(Todo $request)
    {
        $data = $request->all();

        if ($data['attachments'] != null) {
            // GET PREVIOUS FILE NAME FROM DB
            $previous_file = ModelsTodo::where('id', $data['id'])->pluck('attachments')->first();

            // IF PREVIOUS FILE EXIST REMOVE IT FROM STORAGE
            if ($previous_file != null) {
                Storage::delete('/files/' . $previous_file);
            }
            // THAN STORE THE NEW ATTACHMENTS
            $file_name = time() . '_' . $data['attachments']->getClientOriginalName();

            Storage::put('/files/' . $file_name, 'content');
            $data['attachments'] = $file_name;
        }
        ModelsTodo::where('id', $data['id'])->update($data);

        return response()->json([
            'message' => 'record updated successfully!'
        ], 200);
    }

    // GET DELETED TODOS 
    public function deletedTodos()
    {
        $user_id = auth()->id();

        $data = ModelsTodo::onlyTrashed()->latest()->where('user_id', $user_id)->paginate(6);


        return response()->json($data);
    }

    // RESTORE DELETED TODOS 
    public function restore(Request $request)
    {
        $user_id = auth()->id();

        $x = $request->all();

        if ($x != null) {
            foreach ($x as $item) {
                ModelsTodo::where('id', $item)->restore();
            }
        } else {
            ModelsTodo::where('user_id', $user_id)->restore();
        }

        return response()->json([
            'message' =>  'Records restored successfully!',
        ], 200);
    }

    // Export excel file of Todos records
    public function exportData()
    {
        return (new TodosExport)->download('invoices.xlsx');
    }
    // Export excel file of Todos records
    public function importData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new TodosImport, $file);

        
        return response()->json([
            'message' =>  'Data imported successfully!',
        ], 200);    }
}
