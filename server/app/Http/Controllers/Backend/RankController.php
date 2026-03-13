<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\RankDataTable;
use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class RankController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(RankDataTable $dataTable)
    {
        return $dataTable->render('admin.rank.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'max:50'],
                'image' => ['required', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
                'required_points' => ['required', 'integer'],
            ],
            [
                'title.required' => 'Заглавието е задължително',
                'title.max' => 'Заглавието трябва да бъде по-малко от 50 символа',
                'image.required' => 'Изображението е задължително',
                'image.image' => 'Файлът трябва да бъде изображение',
                'image.max' => 'Файлът трябва да бъде по-малък от 2MB',
                'image.mimes' => 'Файлът трябва да бъде във формат: jpg, jpeg, png',
                'required_points.required' => 'Необходимите точки са задължителни',
                'required_points.integer' => 'Необходимите точки трябва да са цяло число'
            ]
        );

        $rank = new Rank();

        $rank->title = $request->title;
        $rank->image = $this->uploadImage($request, 'image', true);
        $rank->required_points = $request->required_points;

        $rank->save();

        toastr('Успешно добавяне на медал!', 'success', 'Успешно добавяне');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rank = Rank::findOrFail($id);

        return view('admin.rank.edit', compact('rank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'title' => ['required', 'max:50'],
                'image' => ['image', 'max:2000', 'mimes:jpg,jpeg,png'],
                'required_points' => ['required', 'integer'],
            ],
            [
                'title.required' => 'Заглавието е задължително',
                'title.max' => 'Заглавието трябва да бъде по-малко от 50 символа',
                'image.image' => 'Файлът трябва да бъде изображение',
                'image.max' => 'Файлът трябва да бъде по-малък от 2MB',
                'image.mimes' => 'Файлът трябва да бъде във формат: jpg, jpeg, png',
                'required_points.required' => 'Необходимите точки са задължителни',
                'required_points.integer' => 'Необходимите точки трябва да са цяло число'
            ]
        );

        $rank = Rank::findOrFail($id);

        $imagePath = $this->updateImage($request, 'image', true, $rank->image);

        $rank->title = $request->title;
        $rank->required_points = $request->required_points;
        $rank->image = empty(!$imagePath) ? $imagePath : $rank->image;

        $rank->save();

        toastr('Успешно редактиране на медал!', 'success', 'Успешно редактиране');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rank = Rank::findOrFail($id);

        if ($rank->users()->count() > 0) {
            toastr('Медала не може да бъде изтрит, защото има потребители, които го използват!', 'error', 'Грешка');
            return redirect()->back();
        }

        $this->deleteImage($rank->image);

        $rank->delete();

        return response(['status' => 'success', 'message' => 'Успешно изтриване!']);
    }
}
