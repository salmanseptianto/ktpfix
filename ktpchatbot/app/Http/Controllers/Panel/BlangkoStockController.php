<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BlangkoAvailability;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlangkoStockController extends Controller
{
    public function create()
    {
        return view('admin.blangko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'no_bast' => 'required|string|max:100',
            'jumlah_blanko' => 'required|integer|min:1',
            'jumlah_total' => 'integer|min:1',

        ]);

        $data = $request->only(['tanggal', 'no_bast', 'jumlah_blanko']);
        $data['id'] = Str::uuid();

        // Ambil jumlah_total terakhir
        $last = BlangkoAvailability::orderByDesc('created_at')->first();
        $jumlah_total_sebelumnya = $last ? $last->jumlah_total : 0;

        $data['jumlah_total'] = $jumlah_total_sebelumnya + (int)$data['jumlah_blanko'];


        BlangkoAvailability::create($data);

        return redirect()->route('admin.blangko.index')->with('success', 'Stok blangko berhasil ditambahkan.');
    }



    public function detail($id)
    {
        $blangko = BlangkoAvailability::findOrFail($id);
        return view('admin.blangko.detail', compact('blangko'));
    }

    public function print($id)
    {
        $blangko = BlangkoAvailability::findOrFail($id);
        $pdf = Pdf::loadView('admin.blangko.pdf', compact('blangko'));
        return $pdf->stream('bast-blangko-' . now()->format('Ymd-His') . '.pdf');
    }
}
