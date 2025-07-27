<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\DocumentRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        $antrianHariIni = Antrian::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->latest()
            ->first();

        $status = 'belum_ambil';
        $docStatus = null; // ✅ tambahkan ini untuk menghindari undefined variable

        if ($antrianHariIni) {
            $docRequest = DocumentRequest::where('antrian_id', $antrianHariIni->id)
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if (!$docRequest) {
                $status = 'ambil_belum_ajukan';
            } elseif ($docRequest->status === 'ditolak') {
                $status = 'ditolak';
            } elseif (in_array($docRequest->status, [
                'dalam proses verifikasi',
                'dalam proses pencetakan',
                'sudah tercetak'
            ])) {
                $status = 'sedang_diproses';
            } elseif ($docRequest->status === 'selesai pengambilan') {
                $status = 'selesai';
            }

            $docStatus = $docRequest->status ?? null;
        }

        // Contoh pengambilan stok blangko
        $blangkoStock = 10; // ganti dengan logika dari database

        return view('users.antrian.index', compact('antrianHariIni', 'status', 'docStatus', 'blangkoStock'));
    }


    public function takeAntrian()
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Cek document request terakhir user (hari ini atau sebelumnya yang belum selesai)
        $lastRequest = DocumentRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($lastRequest) {
            // Jika status masih dalam proses apapun, tolak
            if (in_array($lastRequest->status, [
                'dalam proses verifikasi',
                'dalam proses pencetakan',
                'sudah tercetak'
            ])) {
                return redirect()->route('user.antrian.index')
                    ->with('info', 'Permohonan kamu masih dalam proses, kamu tidak bisa ambil antrian baru.');
            }

            // Jika sudah selesai pengambilan, user tidak bisa ambil lagi hari ini
            if (
                $lastRequest->status === 'selesai pengambilan' &&
                Carbon::parse($lastRequest->created_at)->isToday()
            ) {
                return redirect()->route('user.antrian.index')
                    ->with('info', 'Permohonan kamu sudah selesai hari ini.');
            }
        }

        // Cek apakah ada antrian hari ini
        $existingAntrian = Antrian::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->latest()
            ->first();

        // Jika ada antrian hari ini dan dokumen tidak ditolak, tolak
        if ($existingAntrian) {
            $docRequestToday = DocumentRequest::where('antrian_id', $existingAntrian->id)
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if ($docRequestToday && $docRequestToday->status !== 'ditolak') {
                return redirect()->route('user.antrian.index')
                    ->with('info', 'Kamu sudah memiliki antrian hari ini.');
            }
        }

        // Ambil nomor antrian baru
        $lastNomor = Antrian::whereDate('tanggal', $today)->max('nomor') ?? 0;
        $nextNomor = $lastNomor + 1;

        $antrian = Antrian::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'nomor' => $nextNomor,
            'tanggal' => $today
        ]);

        session(['antrian_id' => $antrian->id]);

        return redirect()->route('user.document.create')
            ->with('success', 'Nomor antrian berhasil diambil.');
    }
}
