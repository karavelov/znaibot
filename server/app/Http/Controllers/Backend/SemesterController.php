<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Klas;
use App\Models\KlasSemester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $klasses = Klas::with(['semester1', 'semester2'])->orderBy('title')->get();
        return view('admin.semesters.index', compact('klasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'semesters.*.1.start' => ['nullable', 'date'],
            'semesters.*.1.end'   => ['nullable', 'date'],
            'semesters.*.2.start' => ['nullable', 'date'],
            'semesters.*.2.end'   => ['nullable', 'date'],
        ]);

        foreach ($request->semesters as $klasId => $semesters) {
            foreach ([1, 2] as $semNum) {
                if (!isset($semesters[$semNum])) continue;

                KlasSemester::updateOrCreate(
                    ['klas_id' => $klasId, 'semester' => $semNum],
                    [
                        'start_date' => $semesters[$semNum]['start'] ?: null,
                        'end_date'   => $semesters[$semNum]['end']   ?: null,
                    ]
                );
            }
        }

        toastr('Сроковете са запазени успешно!', 'success', 'Успешно!');
        return redirect()->back();
    }
}
