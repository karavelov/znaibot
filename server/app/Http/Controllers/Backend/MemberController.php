<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\MemberDataTable;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Year;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(MemberDataTable $dataTable)
    {
        return $dataTable->render('admin.members.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $years = Year::where('status', 1)->orderBy('title', 'asc')->get();

        return view('admin.members.create', compact('years'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'image' => ['nullable', 'image', 'max:3000'],
            'email' => ['email'],
            'years' => ['array'],
        ], [
            'name.required' => 'Полето за име е задължително!',
            'name.max' => 'Името трябва да бъде по-малко от 200 символа!',
            'email.email' => 'Невалиден имейл адрес!',
            'image.image' => 'Файлът трябва да бъде изображение!',
            'image.max' => 'Файлът трябва да бъде по-малък от 3MB!',
            'years.array' => 'Годините трябва да бъдат масив!',
        ]);

        $member = new Member();

        $member->name = $request->name;
        $member->email = $request->email;
        $member->status = $request->status;
        $member->instagram = $request->instagram;
        $member->facebook = $request->facebook;
        $member->phone = $request->phone;


        $imagePath = $this->uploadImage($request, 'image', true);


        $member->image = $imagePath;

        $member->save();

        if ($request->has('years')) {
            $member->years()->attach($request->years);
        }

        toastr('Успешно добавяне', 'success', 'Успешно добавяне!');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::with('years')->findOrFail($id);
        $years = Year::where('status', 1)->orderBy('title', 'asc')->get();

        return view('admin.members.edit', compact('member', 'years'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required'],
            'image' => ['nullable', 'image', 'max:3000'],
            'email' => ['nullable', 'email'],
            'years' => ['array'],
        ],[
            'name.required' => 'Полето за име е задължително!',
            'email.email' => 'Невалиден имейл адрес!',
            'image.image' => 'Файлът трябва да бъде изображение!',
            'image.max' => 'Файлът трябва да бъде по-малък от 3MB!',
            'years.array' => 'Годините трябва да бъдат масив!',
        ]);


        $member = Member::findOrFail($id);

        $member->name = $request->name;
        $member->email = $request->email;
        $member->instagram = $request->instagram;
        $member->facebook = $request->facebook;
        $member->phone = $request->phone;


        $imagePath = $this->updateImage($request, 'image', false, $member->image);


        $member->image = empty(!$imagePath) ? $imagePath : $member->image;

        $member->save();

        // когаго е sync се изтриват всички години и се добавят новите
        if ($request->has('years')) {
            $member->years()->sync($request->years);
        }

        toastr('Успешно редактиране', 'success', 'Успешно редактиране!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);

        // Detach all years associated with the user
        $member->years()->detach();

        $this->deleteImage($member->image);

        $member->delete();

        return response(['status' => 'success', 'message' => 'Годината е изтрита успешно!']);
    }

    public function changeStatus(Request $request)
    {
        $member = Member::findOrFail($request->id);

        $member->status = $request->status == "true" ? 1 : 0;

        $member->save();

        return response(['status' => 'success', 'message' => 'Статусът е актуализиран успешно!']);
    }
}
