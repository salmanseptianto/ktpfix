@extends('layouts.user')

@section('title', 'Ambil Antrian')

@section('content')
	<div class="bg-white p-6 rounded-xl shadow">
		<h2 class="text-xl font-bold mb-6 text-gray-800">Ambil Nomor Antrian</h2>

		{{-- Pesan flash --}}
		@if (session('success'))
			<div class="bg-green-100 text-green-800 p-3 rounded mb-4">
				{{ session('success') }}
			</div>
		@elseif (session('info'))
			<div class="bg-blue-100 text-blue-800 p-3 rounded mb-4">
				{{ session('info') }}
			</div>
		@endif

		{{-- Status Antrian & Aksi --}}
		@if ($status === 'belum_ambil')
			{{-- User belum ambil antrian --}}
			<form action="{{ route('user.antrian.take') }}" method="POST" class="text-center">
				@csrf
				<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded text-lg font-semibold">
					Ambil Nomor Antrian
				</button>
			</form>
		@elseif ($status === 'ambil_belum_ajukan')
			{{-- Sudah ambil antrian, belum ajukan --}}
			<div class="bg-yellow-100 p-4 rounded">
				<p class="text-yellow-800 font-semibold">
					Kamu sudah ambil antrian (No. {{ $antrianHariIni->nomor }}). Lanjutkan pengajuan dokumen.
				</p>
				<a href="{{ route('user.document.create') }}"
					class="inline-block mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
					Lanjutkan Pengajuan
				</a>
			</div>
		@elseif ($status === 'sedang_diproses')
			{{-- Sudah ambil & ajukan, menunggu proses --}}
			<div class="bg-blue-100 p-4 rounded">
				<p class="text-blue-800 font-semibold">
					Permohonan kamu sedang diproses (Status: {{ $docStatus }}). Mohon tunggu.
				</p>
				<a href="{{ route('user.index') }}"
					class="inline-block mt-3 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
					← Kembali
				</a>
			</div>
		@elseif ($status === 'selesai')
			{{-- Sudah selesai --}}
			<div class="bg-green-100 p-4 rounded">
				<p class="text-green-800 font-semibold">
					Permohonan kamu sudah selesai. Kamu tidak bisa ambil antrian lagi.
				</p>
				<a href="{{ route('user.index') }}"
					class="inline-block mt-3 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
					← Kembali
				</a>
			</div>
		@elseif ($status === 'ditolak')
			{{-- Pengajuan ditolak, user boleh ambil antrian baru --}}
			<div class="bg-red-100 p-4 rounded">
				<p class="text-red-800 font-semibold">
					Permohonan kamu ditolak. Kamu bisa ambil nomor antrian baru untuk mengajukan ulang.
				</p>
				<form action="{{ route('user.antrian.take') }}" method="POST" class="mt-3">
					@csrf
					<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded text-lg font-semibold">
						Ambil Nomor Antrian Baru
					</button>
				</form>
			</div>
		@endif
	</div>
@endsection
