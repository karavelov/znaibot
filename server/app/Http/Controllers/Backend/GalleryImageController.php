<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GalleryImageDataTable;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class GalleryImageController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GalleryImageDataTable $dataTable)
    {
        $gallery = Gallery::findOrFail($request->gallery);

        return $dataTable->render('admin.gallery.gallery-images.index', compact('gallery'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);


        $imagePaths=$this->uploadMultiImage($request, 'image', true);

        foreach($imagePaths as $path) {
            $galleryImage=new GalleryImage();
            $galleryImage->image=$path;
            $galleryImage->gallery_id=$request->gallery_id;

            $galleryImage->save();
        }

        toastr('Успешно добавяне на изображение', 'success');

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $galleryImage=GalleryImage::findOrFail($id);

        $this->deleteImage($galleryImage->image);

        $galleryImage->delete();


        return response(['status' => 'success', 'message' => 'Успешно изтриване на избражение!']);
    }
}
