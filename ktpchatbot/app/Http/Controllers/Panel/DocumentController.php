<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\BlangkoAvailability;
use App\Models\DocumentRequest;
use App\Models\Uploads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DocumentController extends Controller
{
    public function create($userId)
    {
        $desaList = [
            'Brebes',
            'Gandasuli',
            'Pasarbatang',
            'Limbangan Wetan',
            'Limbangan Kulon',
            'Randusanga Wetan',
            'Randusanga Kulon',
            'Kaligangsa Wetan',
            'Kaligangsa Kulon',
            'Krasak',
            'Padasugih',
            'Pemaron',
            'Pulosari',
            'Tengki',
            'Wangandalem',
            'Pagejugan',
            'Sigambir',
            'Kalimati',
            'Kaliwlingi',
            'Lembarawa',
            'Kedunguter',
            'Terlangu',
            'Banjaranyar'
        ];

        $user = User::findOrFail($userId);

        $antrian = Antrian::where('user_id', $userId)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if (!$antrian) {
            return redirect()->route('admin.antrian.index')->with('error', 'User belum mengambil antrian hari ini.');
        }

        return view('admin.document.create', compact('desaList', 'user', 'antrian'));
    }

    public function store(Request $request)
    {
        $desaList = [
            'Brebes',
            'Gandasuli',
            'Pasarbatang',
            'Limbangan Wetan',
            'Limbangan Kulon',
            'Randusanga Wetan',
            'Randusanga Kulon',
            'Kaligangsa Wetan',
            'Kaligangsa Kulon',
            'Krasak',
            'Padasugih',
            'Pemaron',
            'Pulosari',
            'Tengki',
            'Wangandalem',
            'Pagejugan',
            'Sigambir',
            'Kalimati',
            'Kaliwlingi',
            'Lembarawa',
            'Kedunguter',
            'Terlangu',
            'Banjaranyar'
        ];

        $request->validate([
            'kk' => 'required|digits_between:8,20',
            'nik' => 'required|digits:16',
            'desa_kelurahan' => 'required|string|in:' . implode(',', $desaList),
            'alasan' => 'required|string',
            'file_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_swafoto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.digits_between' => 'Nomor KK harus terdiri dari 8 sampai 20 digit angka.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'desa_kelurahan.required' => 'Desa/Kelurahan wajib dipilih.',
            'desa_kelurahan.in' => 'Desa/Kelurahan tidak valid.',
            'alasan.required' => 'Alasan permohonan wajib dipilih.',
            'file_kk.required' => 'File KK wajib diupload.',
            'file_kk.mimes' => 'File KK harus berupa JPG, JPEG, PNG, atau PDF.',
            'file_kk.max' => 'Ukuran file KK maksimal 2MB.',
            'file_swafoto.required' => 'Swafoto wajib diupload.',
            'file_swafoto.mimes' => 'Swafoto harus berupa JPG, JPEG, atau PNG.',
            'file_swafoto.max' => 'Ukuran swafoto maksimal 2MB.',
        ]);

        // Ambil stok blangko terakhir
        $stokTerakhir = BlangkoAvailability::orderByDesc('created_at')->first();
        $stokTersedia = $stokTerakhir ? $stokTerakhir->jumlah_total : 0;

        if ($stokTersedia <= 0) {
            return redirect()->back()->with('error', 'Stok blangko tidak mencukupi. Permintaan tidak dapat diproses.');
        }

        DB::beginTransaction();

        try {
            $antrianHariIni = Antrian::where('id', $request->antrian_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$antrianHariIni) {
                return redirect()->back()->with('error', 'Anda belum mengambil nomor antrian hari ini.');
            }

            // Simpan dokumen request dulu
            $documentRequest = DocumentRequest::create([
                'id' => Str::uuid(),
                'user_id' => $request->user_id,
                'antrian_id' => $antrianHariIni->id,
                'kk' => $request->kk,
                'nik' => $request->nik,
                'desa_kelurahan' => $request->desa_kelurahan,
                'alasan' => $request->alasan,
                'status' => 'dalam proses verifikasi',
            ]);

            // Upload file
            $upload = new Uploads();
            $upload->id = Str::uuid();
            $upload->request_id = $documentRequest->id;

            if ($request->hasFile('file_kk')) {
                $upload->file_kk = $request->file('file_kk')->store('uploads/kk', 'public');
            }
            if ($request->hasFile('file_ktp_lama')) {
                $upload->file_ktp_lama = $request->file('file_ktp_lama')->store('uploads/ktp_lama', 'public');
            }
            if ($request->hasFile('file_surat_kehilangan')) {
                $upload->file_surat_kehilangan = $request->file('file_surat_kehilangan')->store('uploads/surat_kehilangan', 'public');
            }
            $upload->file_swafoto = $request->file('file_swafoto')->store('uploads/swafoto', 'public');
            // if ($request->hasFile('file_swafoto')) {
            // }

            $upload->save();

            $stokBaru = $stokTersedia - 1;
            $stokTerakhir->update(['jumlah_total' => $stokBaru]);

            DB::commit();

            return redirect()->route('admin.document.index')->with('success', 'Permohonan berhasil ditambahkan dan stok blangko dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan permintaan: ' . $e->getMessage());
        }
    }

    public function detailShow($id)
    {
        $request = DocumentRequest::with(['user', 'upload'])->findOrFail($id);
        return view('admin.document.detail', compact('request'));
    }
    public function detailPop($id)
    {
        $request = DocumentRequest::with(['user', 'upload'])->findOrFail($id);
        return view('admin.document._detail_modal', compact('request'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:dalam proses verifikasi,dalam proses pencetakan,sudah tercetak,selesai pengambilan,ditolak',
            'alasan_ditolak' => 'nullable|string|max:255',
        ]);

        $document = DocumentRequest::findOrFail($id);
        $previousStatus = $document->status;
        $document->status = $request->status;

        if ($request->status === 'ditolak') {
            $document->alasan_ditolak = $request->alasan_ditolak;

            // Kembalikan stok blangko jika sebelumnya bukan ditolak
            if ($previousStatus !== 'ditolak') {
                $stokTerakhir = BlangkoAvailability::orderByDesc('created_at')->first();
                if ($stokTerakhir) {
                    $stokTerakhir->update(['jumlah_total' => $stokTerakhir->jumlah_total + 1]);
                }
            }
        } else {
            $document->alasan_ditolak = null;
        }
        $document->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
        }

        return redirect()->route('admin.document.index', $document->id)->with('success', 'Status berhasil diperbarui.');
    }

    public function printHistory()
    {
        $requests = DocumentRequest::with('user')->latest()->get();

        return view('admin.document.print', compact('requests'));
    }

    public function print(Request $request)
    {
        $query = DocumentRequest::with('user');

        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        $bulan = $request->bulan ? Carbon::create()->month($request->bulan)->translatedFormat('F') : 'Semua';
        $tahun = $request->tahun ?? 'Semua';

        $pdf = Pdf::loadView('admin.document.pdf', compact('requests', 'bulan', 'tahun'));

        return $pdf->stream('laporan-dokumen-' . $bulan . '-' . $tahun . '.pdf');
    }
}