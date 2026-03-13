<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CustomerListDataTable;
use App\Helper\MailHelper;
use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\Allergen;
use App\Models\Klas;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerListController extends Controller
{
    use ImageUploadTrait;

    public function index(CustomerListDataTable $dataTable)
    {
        return $dataTable->render('admin.customer-list.index');
    }


    public function create()
    {
        $fathers = User::where('role', 'parent')->where('gender', 'male')->get();
        $mothers = User::where('role', 'parent')->where('gender', 'female')->get();
        $klasses = Klas::orderBy('title')->get();
        return view('admin.customer-list.create', compact('fathers', 'mothers', 'klasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'image' => ['nullable', 'image', 'max:3000'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'citizenship' => ['nullable', 'string', 'max:255'],
            'parent_father_id' => ['nullable', 'exists:users,id'],
            'parent_mother_id' => ['nullable', 'exists:users,id'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'doctor_phone' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'nfc_id' => ['nullable', 'string', 'max:255', 'unique:users,nfc_id'],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = 'active';
        $user->instagram = $request->instagram;
        $user->facebook = $request->facebook;
        $user->phone = $request->phone;
        $user->birth_date = $request->birth_date;
        $user->birth_place = $request->birth_place;
        $user->citizenship = $request->citizenship;
        $user->doctor_name = $request->doctor_name;
        $user->doctor_phone = $request->doctor_phone;
        $user->gender  = $request->gender;
        $user->nfc_id  = $request->nfc_id ?: null;

        if ($request->role === 'student') {
            $user->parent_father_id = $request->parent_father_id;
            $user->parent_mother_id = $request->parent_mother_id;
            $user->klas_id = $request->klas_id ?: null;
        } else {
            $user->klas_id = null;
        }

        if ($request->role === 'teacher') {
            $user->homeroom_klas_id = $request->homeroom_klas_id ?: null;
        } else {
            $user->homeroom_klas_id = null;
        }

        $imagePath = $this->uploadImage($request, 'image', true);
        $user->image = $imagePath;

        $user->save();

        // set mail config
        MailHelper::setMailConfig();
        // Mail::to($request->email)->send(new AccountCreatedMail($request->name, $request->email, $request->password));

        toastr('Успешно създаден потребител', 'success', 'Успешно създаване!');
        return redirect()->back();
    }


    public function changeStatus(Request $request)
    {
        $customer = User::findOrFail($request->id);
        $customer->status = $request->status == 'true' ? 'active' : 'inactive';
        $customer->save();

        return response(['message' => 'Статусът е актуализиран успешно!']);
    }

    public function edit(string $id)
    {
        $customer    = User::with('allergens')->findOrFail($id);
        $fathers     = User::where('role', 'parent')->where('gender', 'male')->get();
        $mothers     = User::where('role', 'parent')->where('gender', 'female')->get();
        $klasses     = Klas::orderBy('title')->get();
        $allAllergens = Allergen::orderBy('name')->get();

        return view('admin.customer-list.edit', compact('customer', 'fathers', 'mothers', 'klasses', 'allAllergens'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required'],
            'image' => ['nullable', 'image', 'max:3000'],
            'email' => ['required', 'email'],
            'role' => ['required'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'citizenship' => ['nullable', 'string', 'max:255'],
            'parent_father_id' => ['nullable', 'exists:users,id'],
            'nfc_id' => ['nullable', 'string', 'max:255', 'unique:users,nfc_id,' . $id],
            'parent_mother_id' => ['nullable', 'exists:users,id'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'doctor_phone' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
        ]);


        $customer = User::findOrFail($id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->role = $request->role;
        $customer->instagram = $request->instagram;
        $customer->facebook = $request->facebook;
        $customer->phone = $request->phone;
        $customer->birth_date = $request->birth_date;
        $customer->birth_place = $request->birth_place;
        $customer->citizenship = $request->citizenship;
        $customer->doctor_name = $request->doctor_name;
        $customer->doctor_phone = $request->doctor_phone;
        $customer->gender  = $request->gender;
        $customer->nfc_id  = $request->nfc_id ?: null;

        if ($request->role === 'student') {
            $customer->parent_father_id = $request->parent_father_id;
            $customer->parent_mother_id = $request->parent_mother_id;
            $customer->klas_id = $request->klas_id ?: null;
        } else {
            $customer->parent_father_id = null;
            $customer->parent_mother_id = null;
            $customer->klas_id = null;
        }

        if ($request->role === 'teacher') {
            $customer->homeroom_klas_id = $request->homeroom_klas_id ?: null;
        } else {
            $customer->homeroom_klas_id = null;
        }

        $imagePath = $this->updateImage($request, 'image', false, $customer->image);
        $customer->image = empty(!$imagePath) ? $imagePath : $customer->image;

        $customer->save();


        toastr('Успешно редактиране', 'success', 'Success');

        return redirect()->back();
    }


    public function update_password(Request $request, string $id)
    {

        $request->validate(
            [
                'password' => ['required', 'min:8', 'confirmed'],
            ],
            [
                'password.required' => 'Полето за парола е задължително!',
                'password.min' => 'Паролата трябва да бъде поне 8 символа!',
                'password.confirmed' => 'Паролите не съвпадат!',
            ]
        );

        $user = User::findOrFail($id);

        $user->update([
            'password' => bcrypt($request->password),
        ]);


        // send email to user that his password has been changed
        try {
            Mail::raw('Вашата парола беше променена! Ако не сте били вие, моля веднага променете паролата си тук: <a href=' . route('password.request') . '>Промяна на парола</a>', function ($message) use ($user) {
                $message->from(env('MAIL_FROM_ADDRESS'), 'E-Laravel');
                $message->to($user->email, $user->name)
                    ->subject('Заявка за промяна на парола');
            });
        } catch (\Exception $e) {
            // Handle the error, you can log it
            toastr('Грешка при изпращане на имейл!', 'error', 'Error');
        }

        toastr('Паролата е променена успешно!', 'success', 'Success');

        return redirect()->back();
    }

    public function delete(string $id)
    {

        $user = User::findOrFail($id);


        $this->deleteImage($user->image);

        $user->delete();

        return response(['status' => 'success', 'message' => 'Изтрито успешно!']);
    }
}
