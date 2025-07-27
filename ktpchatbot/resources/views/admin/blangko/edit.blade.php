@extends('layouts.admin')

@section('title', 'Edit Stok Blangko')

@section('content')
	<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
		<h1 class="text-xl font-semibold mb-4">Edit Stok Blangko KTP Elektronik</h1>

		<form action="{{ route('admin.blangko.update', $blangko->id) }}" method="POST">
			@csrf
			@method('PUT')

			<div class="mb-4">
				<label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
				<input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $blangko->tanggal) }}"
					class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('tanggal')
					<p class="text-red-600 text-sm mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div class="mb-4">
				<label for="no_bast" class="block text-sm font-medium text-gray-700">Nomor BAST</label>
				<input type="text" name="no_bast" id="no_bast" value="{{ old('no_bast', $blangko->no_bast) }}"
					class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('no_bast')
					<p class="text-red-600 text-sm mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div class="mb-4">
				<label for="jumlah_blanko" class="block text-sm font-medium text-gray-700">Jumlah Blangko</label>
				<input type="number" name="jumlah_blanko" id="jumlah_blanko" min="1"
					value="{{ old('jumlah_blanko', $blangko->jumlah_blanko) }}"
					class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('jumlah_blanko')
					<p class="text-red-600 text-sm mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div class="flex justify-end">
				<a href="{{ route('admin.blangko.index') }}"
					class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Batal</a>
				<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
			</div>
		</form>
	</div>
@endsection
