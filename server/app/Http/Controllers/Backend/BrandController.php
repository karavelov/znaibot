<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ImageUploadTrait;

class BrandController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDataTable $dataTable)
    {
        return $dataTable->render('admin.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'name' => ['required', 'max:200'],
            'is_featured' => ['required'],
            'status' => ['required']
        ],
        [
            'image.required' => 'Изображението е задължително',
            'image.image' => 'Файлът трябва да бъде изображение',
            'image.max' => 'Файлът трябва да бъде по-малък от 2MB',
            'image.mimes' => 'Файлът трябва да бъде във формат: jpg, jpeg, png',
            'name.required' => 'Името е задължително',
            'name.max' => 'Името трябва да бъде по-малко от 200 символа',
            'is_featured.required' => 'Статус на изложение е задължителна',
            'status.required' => 'Статусът е задължителен'
        ]
    
    );

        $brand=new Brand();

        $brand->logo=$this->uploadImage($request, 'image', true);
        $brand->name=$request->name;
        $brand->slug=Str::slug($request->name);
        $brand->is_featured=$request->is_featured;
        $brand->status=$request->status;

        $brand->save();

        toastr('Успешно добавяне на партньор!', 'success', 'Успешно добавяне');

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
        $brand=Brand::findOrFail($id);

        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => ['image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'name' => ['required', 'max:200'],
            'is_featured' => ['required'],
            'status' => ['required']
        ],
        [
            'image.image' => 'Файлът трябва да бъде изображение',
            'image.max' => 'Файлът трябва да бъде по-малък от 2MB',
            'image.mimes' => 'Файлът трябва да бъде във формат: jpg, jpeg, png',
            'name.required' => 'Името е задължително',
            'name.max' => 'Името трябва да бъде по-малко от 200 символа',
            'is_featured.required' => 'Статус на изложение е задължителна',
            'status.required' => 'Статусът е задължителен'
        ]
    );

        $brand=Brand::findOrFail($id);

        $imagePath = $this->updateImage($request, 'image', true, $brand->logo);

        $brand->logo = empty(!$imagePath) ? $imagePath : $brand->logo;
        $brand->name=$request->name;
        $brand->slug=Str::slug($request->name);
        $brand->is_featured=$request->is_featured;
        $brand->status=$request->status;

        $brand->save();

        toastr('Успешно редактиране на партньор', 'success', 'Успешно редактиране');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand=Brand::findOrFail($id);


        $this->deleteImage($brand->logo);

        $brand->delete();


        return response(['status' => 'success', 'message' => 'Успешно изтриване!']);
    }

    public function changeStatus(Request $request) {
        $brand=Brand::findOrFail($request->id);

        $brand->status=$request->status == "true" ? 1 : 0;

        $brand->save();

        return response(['status' => 'success', 'message' => 'Статусът е актуализиран успешно!']);
    }
}
