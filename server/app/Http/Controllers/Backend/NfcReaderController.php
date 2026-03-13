<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\NfcReaderDataTable;
use App\Http\Controllers\Controller;
use App\Models\NfcReader;
use Illuminate\Http\Request;

class NfcReaderController extends Controller
{
    public function index(NfcReaderDataTable $dataTable)
    {
        return $dataTable->render('admin.nfc-readers.index');
    }

    public function create()
    {
        return view('admin.nfc-readers.create', ['typeLabels' => NfcReader::$typeLabels]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type'  => ['required', 'in:door_in,door_out,robot,other'],
        ]);

        NfcReader::create($request->only('title', 'type'));

        return redirect()->route('admin.nfc-readers.index')
            ->with('success', 'NFC четецът е добавен успешно.');
    }

    public function edit(NfcReader $nfcReader)
    {
        return view('admin.nfc-readers.edit', [
            'reader'     => $nfcReader,
            'typeLabels' => NfcReader::$typeLabels,
        ]);
    }

    public function update(Request $request, NfcReader $nfcReader)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type'  => ['required', 'in:door_in,door_out,robot,other'],
        ]);

        $nfcReader->update($request->only('title', 'type'));

        return redirect()->route('admin.nfc-readers.index')
            ->with('success', 'NFC четецът е обновен успешно.');
    }

    public function destroy(NfcReader $nfcReader)
    {
        $nfcReader->delete();

        return redirect()->route('admin.nfc-readers.index')
            ->with('success', 'NFC четецът е изтрит.');
    }
}
