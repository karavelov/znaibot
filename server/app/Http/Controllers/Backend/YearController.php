<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\MemberYearDataTable;
use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MemberYearDataTable $dataTable)
    {
        return $dataTable->render('admin.member-years.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.member-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'max:255'],
                'status' => ['required']
            ],
            [
                'title.required' => 'Полето за заглавие е задължително!',
                'title.max' => 'Заглавието трябва да бъде по-малко от 255 символа!',
                'status.required' => 'Полето за статус е задължително!'
            ]
        );

        $memberYear = new Year();

        $memberYear->title = $request->title;
        $memberYear->status = $request->status;

        $memberYear->save();

        toastr('Успешно добавяне', 'success', 'Успешно добавяне');

        return redirect()->route('admin.member-years.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $memberYear = Year::findOrFail($id);

        return view('admin.member-years.edit', compact('memberYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'title' => ['required', 'max:255'],
                'status' => ['required']
            ],
            [
                'title.required' => 'Полето за заглавие е задължително!',
                'title.max' => 'Заглавието трябва да бъде по-малко от 255 символа!',
                'status.required' => 'Полето за статус е задължително!'
            ]
        );

        $memberYear = Year::findOrFail($id);

        $memberYear->title = $request->title;
        $memberYear->status = $request->status;

        $memberYear->save();

        toastr('Успешно редактиране', 'success', 'Успешно редактиране!');

        return redirect()->route('admin.member-years.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $memberYear = Year::findOrFail($id);

        if ($memberYear->users()->exists()) {
            return response(['status' => 'error', 'message' => 'Тази година има потребители. Моля, първо ги изтрийте!']);
        }

          // за краен случай, ако има потребители, които са свързани с години в user_years
        $memberYear->users()->detach();

        $memberYear->delete();

        return response(['status' => 'success', 'message' => 'Годината е изтрита успешно!']);
    }

    public function changeStatus(Request $request)
    {
        $memberYear = Year::findOrFail($request->id);

        $memberYear->status = $request->status == "true" ? 1 : 0;

        $memberYear->save();

        return response(['status' => 'success', 'message' => 'Статусът е актуализиран успешно!']);
    }
}
