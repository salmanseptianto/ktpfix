@extends('layouts.user')

@section('title', 'Dashboard Warga')

@section('content')
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex items-center justify-center">
        <div class="bg-white/90 p-8 rounded-2xl shadow-lg max-w-xl w-full">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl mb-8 border border-blue-100">
                <h2 class="text-xl font-bold text-blue-800 mb-1">
                    Selamat Datang,
                    <span class="text-2xl md:text-2xl font-extrabold text-blue-900">
                        {{ Auth::user()->name ?? 'Nama!' }}
                    </span>
                </h2>
                <p class="text-gray-600 mt-2">
                    Silakan mengajukan permohonan KTP secara online atau<br>
                    cek status permohonan Anda di sini.
                </p>
                {{-- Status Blangko --}}
                <div class="mt-6">
                    <span
                        class="inline-block text-sm font-semibold rounded-lg px-4 py-2 border
    {{ $blangkoStock > 0 ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                        {{ $blangkoStock > 0 ? 'Blangko tersedia saat ini (' . $blangkoStock . ' blangko)' : 'Blangko tidak tersedia' }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center gap-4">
                @if ($blangkoStock > 0)
                    <a href="{{ route('user.antrian.index') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-6 py-3 text-center w-full md:w-auto shadow transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Ajukan Permohonan Cetak
                    </a>
                @else
                    <button disabled
                        class="bg-gray-400 text-white font-semibold rounded-lg px-6 py-3 text-center w-full md:w-auto shadow transition-all duration-150 cursor-not-allowed">
                        Ajukan Permohonan Cetak
                    </button>
                @endif
                <a href="{{ route('user.document.status') }}"
                    class="bg-white border border-blue-200 hover:bg-blue-50 text-blue-700 font-semibold rounded-lg px-6 py-3 text-center w-full md:w-auto shadow transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    Lihat Riwayat Pengajuan
                </a>
            </div>
        </div>
    </div>
@endsection
