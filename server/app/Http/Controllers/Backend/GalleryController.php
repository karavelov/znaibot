<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GalleryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GalleryDataTable $dataTable)
    {
        return $dataTable->render('admin.gallery.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery.create');
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

        $gallery = new Gallery();

        $gallery->title = $request->title;
        $gallery->status = $request->status;

        $gallery->save();

        toastr('Успешно създадена галерия', 'success', 'Успешно добавяне');

        return redirect()->route('admin.gallery.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery=Gallery::findOrFail($id);

        return view('admin.gallery.edit',compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'status' => ['required']
        ],
            [
                'title.required' => 'Полето за заглавие е задължително!',
                'title.max' => 'Заглавието трябва да бъде по-малко от 255 символа!',
                'status.required' => 'Полето за статус е задължително!'
            ]
        );

        $gallery = Gallery::findOrFail($id);

        $gallery->title = $request->title;
        $gallery->status = $request->status;

        $gallery->save();

        toastr('Успешно редактирана галерия', 'success', 'Успешно редактиране');

        return redirect()->route('admin.gallery.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery=Gallery::findOrFail($id);
        $galleryImages=GalleryImage::where('gallery_id',$id)->count();

        if($galleryImages > 0) {
            return response(['status' => 'error', 'message' => 'Галерията не може да бъде изтрита, защото съдържа изображения!']);
        }

        $gallery->delete();
        
      
        return response(['status' => 'success', 'message' => 'Галерията е изтрита успешно!']);
    }


    public function changeStatus(Request $request)
    {
        $gallery = Gallery::findOrFail($request->id);

        $gallery->status = $request->status == "true" ? 1 : 0;

        $gallery->save();

        return response(['status' => 'success', 'message' => 'Статусът е актуализиран успешно!']);
    }
}
