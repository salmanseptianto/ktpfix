<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BlangkoAvailability;
use App\Models\DocumentRequest;
use App\Models\RegistHistorys;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total permintaan dokumen
        $documentRequest = DocumentRequest::count();

        // Stok blangko
        $blangkoStock = optional(BlangkoAvailability::orderByDesc('created_at')->first())->jumlah_total ?? 0;

        // (opsional) Jumlah user
        $userCount = User::where('role', 'user')->count();

        // Data grafik: jumlah pengajuan per bulan (1 tahun terakhir)
        $year = now()->year;
        $monthlyData = [];
        foreach (range(1, 12) as $m) {
            $monthlyData[] = DocumentRequest::whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Data pie: jumlah alasan per bulan (bulan ini)
        $month = now()->month;
        $alasanList = ['baru', 'rusak', 'hilang', 'pembaruan data'];
        $pieData = [];
        foreach ($alasanList as $alasan) {
            $pieData[$alasan] = DocumentRequest::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('alasan', $alasan)
                ->count();
        }

        $latestRequests = DocumentRequest::with('user')->latest()->take(3)->get();

        $blangkoThreshold = 10;
        $blangkoWarning = $blangkoStock < $blangkoThreshold;

        return view('admin.index', compact(
            'documentRequest',
            'blangkoStock',
            'userCount',
            'monthlyData',
            'pieData',
            'year',
            'month',
            'latestRequests',
            'blangkoWarning'
        ));
    }

    public function document(Request $request)
    {
        $query = DocumentRequest::with('user');

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }
        if ($request->filled('alasan')) {
            $query->where('alasan', $request->alasan);
        }

        $requests = $query->latest()->get();

        return view('admin.document.index', compact('requests'));
    }

    public function blangko()
    {
        // Ambil stok terakhir (paling baru)
        $stokSaatIni = optional(BlangkoAvailability::orderByDesc('created_at')->first())->jumlah_total ?? 0;

        // Ambil seluruh riwayat update stok (untuk tabel/laporan)
        $riwayat = BlangkoAvailability::orderByDesc('tanggal')->get();

        return view('admin.blangko.index', [
            'stokSaatIni' => $stokSaatIni,
            'riwayat' => $riwayat,
        ]);
    }

    public function users()
    {
        $users = User::where('role', 'user')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function takeEktp()
    {
        $ektp = DocumentRequest::with('user')->where('status', 'sudah tercetak')->latest()->get();
        return view('admin.document.take_ektp.index', compact('ektp'));
    }
}