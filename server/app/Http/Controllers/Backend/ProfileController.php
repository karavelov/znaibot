<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {

        return view('admin.profile.index');
    }

    public function update_profile(Request $request)
    {

        $request->validate(
            [
                'name' => ['required', 'max:100'],
                'email' => ['required', 'email', 'unique:users,email,' . Auth::user()->id],
                'image' => ['image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            ],
            [
                'image.mimes' => 'Снимката трябва да бъде във формат: jpeg, png, jpg, svg',
                'image.max' => 'Снимката трябва да бъде по-малка от 2MB',
                'email.unique' => 'Имейл адресът вече съществува',
                'email.required' => 'Имейл адресът е задължителен',
                'email.email' => 'Имейл адресът трябва да бъде валиден',
                'name.required' => 'Името е задължително',
                'name.max' => 'Името трябва да бъде по-малко от 100 символа',
                'image.required' => 'Снимката е задължителна',
            ]
        );

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('image')) {
            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }

            $ddate = date('Y/m');
            $godina = date('Y');
            $mesec = date('m');
            $WRITEDIR = "uploads/" . $godina;
            if (!is_dir($WRITEDIR)) {
                mkdir($WRITEDIR, 0777);
            };
            $WRITEDIR = "uploads/" . $godina . "/" . $mesec;
            if (!is_dir($WRITEDIR)) {
                mkdir($WRITEDIR, 0777);
            };
            $NORMALDIR = "$WRITEDIR";
            $THUMBDIR1 = "$WRITEDIR/t1";
            $THUMBDIR2 = "$WRITEDIR/t2";
            $THUMBDIR3 = "$WRITEDIR/t3";
            $THUMBDIR4 = "$WRITEDIR/t4";
            $THUMBDIR5 = "$WRITEDIR/t5";
            $THUMBDIR6 = "$WRITEDIR/t6";
            $THUMBDIR7 = "$WRITEDIR/t7";
            $THUMBDIR8 = "$WRITEDIR/t8";
            if (!is_dir("$THUMBDIR1")); {
                @mkdir("$THUMBDIR1", 0777);
            };
            if (!is_dir("$THUMBDIR2")); {
                @mkdir("$THUMBDIR2", 0777);
            };
            if (!is_dir("$THUMBDIR3")); {
                @mkdir("$THUMBDIR3", 0777);
            };
            if (!is_dir("$THUMBDIR4")); {
                @mkdir("$THUMBDIR4", 0777);
            };
            if (!is_dir("$THUMBDIR5")); {
                @mkdir("$THUMBDIR5", 0777);
            };
            if (!is_dir("$THUMBDIR6")); {
                @mkdir("$THUMBDIR6", 0777);
            };
            if (!is_dir("$THUMBDIR7")); {
                @mkdir("$THUMBDIR7", 0777);
            };
            if (!is_dir("$THUMBDIR8")); {
                @mkdir("$THUMBDIR8", 0777);
            };

            $image = $request->image;
            $image_name = rand() . '_' . $image->getClientOriginalName();
            $image->move($NORMALDIR, $image_name);

            $path = $NORMALDIR . "/" . $image_name;

            $user->image = $path;
        }

        $user->save();

        toastr()->success('Профилът е актуализиран успешно');

        return redirect()->back();
    }

    public function update_password(Request $request)
    {
        $request->validate(
            [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'min:8', 'confirmed']
            ],
            [
                'current_password.required' => 'Текущата парола е задължителна',
                'current_password.current_password' => 'Текущата парола не е валидна',
                'password.required' => 'Новата парола е задължителна',
                'password.min' => 'Новата парола трябва да бъде поне 8 символа',
                'password.confirmed' => 'Потвърждението на новата парола не съвпада'
            ]

        );

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        toastr()->success('Паролата е актуализирана успешно');

        return redirect()->back();
    }
}
