@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
	<div class="bg-white rounded-xl shadow p-6">
		<h2 class="text-xl font-bold text-blue-700 mb-4">Tambah User Baru</h2>

		@if (session('error'))
			<div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 rounded">
				{{ session('error') }}
			</div>
		@endif

		@if ($errors->any())
			<div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 rounded">
				<ul class="list-disc pl-5">
					@foreach ($errors->all() as $err)
						<li>{{ $err }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form action="{{ route('admin.users.store') }}" method="POST">
			@csrf
			<div class="mb-3">
				<label class="block mb-1 font-semibold">Nama</label>
				<input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
			</div>
			<div class="mb-3">
				<label class="block mb-1 font-semibold">Email</label>
				<input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
			</div>
			<div class="mb-3">
				<label class="block mb-1 font-semibold">NIK</label>
				<input type="text" name="nik" class="w-full border rounded px-3 py-2" value="{{ old('nik') }}" required>
			</div>
			<div class="mb-3">
				<label class="block mb-1 font-semibold">No. HP</label>
				<input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}" required>
			</div>
			<div class="mb-3">
				<label class="block mb-1 font-semibold">Password</label>
				<input type="password" name="password" class="w-full border rounded px-3 py-2" required>
			</div>
			<div class="mb-4">
				<label class="block mb-1 font-semibold">Konfirmasi Password</label>
				<input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
			</div>
			<div class="flex justify-between">
				<a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Kembali</a>
				<button type="submit"
					class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Simpan</button>
			</div>
		</form>
	</div>
@endsection
