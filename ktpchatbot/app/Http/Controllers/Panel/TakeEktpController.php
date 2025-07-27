<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use App\Models\TakeEktp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TakeEktpController extends Controller
{
    public function show($requestId)
    {
        $request = DocumentRequest::findOrFail($requestId);
        return view('admin.document.take_ektp.input', compact('request'));
    }

    // Simpan data pengambilan
    public function store(Request $request, $requestId)
    {
        $request->validate([
            'nama_pengambil' => 'required|string|max:255',
            'tanggal_pengambilan' => 'required|date',
        ]);

        $documentRequest = DocumentRequest::findOrFail($requestId);

        // Simpan data pengambilan
        TakeEktp::create([
            'id' => Str::uuid(),
            'request_id' => $documentRequest->id,
            'nama_pengambil' => $request->nama_pengambil,
            'tanggal_pengambilan' => $request->tanggal_pengambilan,
        ]);

        // Update status dokumen
        $documentRequest->status = 'selesai pengambilan';
        $documentRequest->save();

        return redirect()->route('admin.document.takeEktp')->with('success', 'Data pengambilan berhasil disimpan.');
    }
}