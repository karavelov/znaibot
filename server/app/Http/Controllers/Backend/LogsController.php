<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserFact;
use Illuminate\Http\Request;

class LogsController extends Controller
{

    public function index(Request $request)
    {
        $logs = UserFact::with('user:id,name')->orderBy('id', 'desc')->paginate(10);
        
        return view('admin.logs.index', compact('logs'));
    }

    public function getData(Request $request)
    {
        $logs = UserFact::with('user:id,name')->select(['id', 'text', 'userid']);
        
        return datatables()->of($logs)
            ->addColumn('user_name', function($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->rawColumns(['user_name'])
            ->make(true);
    }


    public function destroy(string $id)
    {
        $log = UserFact::findOrFail($id);
        $log->delete();

        return response(['status' => 'success', 'message' => 'Записът е изтрит успешно!']);
    }
}