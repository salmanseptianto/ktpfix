<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\BlangkoAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentRequest;
use App\Models\Uploads;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
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
        $stokTerakhir = \App\Models\BlangkoAvailability::orderByDesc('created_at')->first();
        $stokTersedia = $stokTerakhir ? $stokTerakhir->jumlah_total : 0;

        if ($stokTersedia <= 0) {
            return redirect()->back()->with('error', 'Stok blangko tidak mencukupi. Permintaan tidak dapat diproses.');
        }

        DB::beginTransaction();

        try {
            $antrianHariIni = Antrian::where('user_id', auth()->id())
                ->whereDate('tanggal', now()->toDateString())
                ->first();

            if (!$antrianHariIni) {
                return redirect()->back()->with('error', 'Anda belum mengambil nomor antrian hari ini.');
            }

            $documentRequest = DocumentRequest::create([
                'id' => Str::uuid(),
                'user_id' => auth()->id(),
                'antrian_id' => $antrianHariIni->id,
                'kk' => $request->kk,
                'nik' => $request->nik,
                'desa_kelurahan' => $request->desa_kelurahan, // mapping dari form user
                'alasan' => $request->alasan,
                'status' => 'dalam proses verifikasi',
            ]);

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

            return redirect()->route('user.index')->with('success', 'Permintaan dokumen berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan permintaan: ' . $e->getMessage());
        }
    }

    public function status()
    {
        $requests = DocumentRequest::with(['upload', 'statusLogs', 'antrian'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('users.document.status', compact('requests'));
    }
}