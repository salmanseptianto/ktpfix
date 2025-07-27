<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\DocumentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $users = User::where('role', 'user')
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->get();

        $today = Carbon::today();
        $antriansToday = Antrian::whereDate('tanggal', $today)->get()->keyBy('user_id');
        $pengajuansToday = DocumentRequest::whereDate('created_at', $today)->get()->groupBy('user_id');

        // Ambil status dokumen terakhir untuk setiap user
        $userDocStatuses = [];
        foreach ($users as $user) {
            $lastDoc = DocumentRequest::where('user_id', $user->id)
                ->latest()
                ->first();
            $userDocStatuses[$user->id] = $lastDoc ? $lastDoc->status : null;
        }

        return view('admin.antrian.index', compact(
            'users',
            'antriansToday',
            'pengajuansToday',
            'today',
            'keyword',
            'userDocStatuses'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $userId = $request->user_id;
        $today = Carbon::today();

        // Cek document request terakhir
        $lastRequest = DocumentRequest::where('user_id', $userId)
            ->latest()
            ->first();

        if ($lastRequest && in_array($lastRequest->status, [
            'dalam proses verifikasi',
            'dalam proses pencetakan',
            'sudah tercetak',
            'selesai pengambilan'
        ])) {
            return redirect()->route('admin.antrian.index')
                ->with('info', 'User ini masih memiliki pengajuan yang belum selesai.');
        }

        // Jika sudah ada antrian hari ini & tidak ditolak, tolak
        $existingAntrian = Antrian::where('user_id', $userId)->whereDate('tanggal', $today)->first();
        if ($existingAntrian) {
            $docRequest = DocumentRequest::where('antrian_id', $existingAntrian->id)
                ->where('user_id', $userId)
                ->latest()
                ->first();

            if ($docRequest && $docRequest->status !== 'ditolak') {
                return redirect()->route('admin.antrian.index')
                    ->with('info', 'User ini sudah memiliki antrian hari ini.');
            }
        }

        // Ambil nomor antrian baru
        $lastNomor = Antrian::whereDate('tanggal', $today)->max('nomor') ?? 0;
        $nextNomor = $lastNomor + 1;

        $antrian = Antrian::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'nomor' => $nextNomor,
            'tanggal' => $today
        ]);

        return redirect()->route('admin.document.create', [
            'userId' => $userId
        ]);
    }
}
