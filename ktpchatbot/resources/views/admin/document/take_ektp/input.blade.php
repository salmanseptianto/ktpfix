@extends('layouts.admin')

@section('title', 'Input Pengambilan e-KTP')

@section('content')
	<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6 mt-8">
		<h2 class="text-xl font-bold mb-4">Input Pengambilan e-KTP</h2>
		<form action="{{ route('admin.document.takeEktp.store', $request->id) }}" method="POST">
			@csrf
			<div class="mb-4">
				<label for="nama_pengambil" class="block text-sm font-medium mb-1">Nama Pengambil</label>
				<input type="text" name="nama_pengambil" id="nama_pengambil" class="w-full border rounded px-3 py-2" required>
			</div>
			<div class="mb-4">
				<label for="tanggal_pengambilan" class="block text-sm font-medium mb-1">Tanggal Pengambilan</label>
				<input type="date" name="tanggal_pengambilan" id="tanggal_pengambilan"
					class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ date('Y-m-d') }}" readonly required>
			</div>
			<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
				Simpan
			</button>
		</form>
	</div>
@endsection
