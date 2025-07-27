@extends('layouts.auth')

@section('content')
	<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
		<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-md">
			<h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register</h2>

			{{-- Alert error umum --}}
			@if (session('error'))
				<div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm text-center">
					{{ session('error') }}
				</div>
			@endif

			<form method="POST" action="{{ route('register') }}" class="space-y-4">
				@csrf

				{{-- NIK --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">NIK</label>
					<input type="text" name="nik" value="{{ old('nik') }}"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
					@error('nik')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Nama Lengkap --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">Nama Lengkap</label>
					<input type="text" name="name" value="{{ old('name') }}"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
					@error('name')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Email --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">Email</label>
					<input type="email" name="email" value="{{ old('email') }}"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
					@error('email')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- No. HP --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">No. HP</label>
					<input type="text" name="phone" value="{{ old('phone') }}"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
					@error('phone')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Password --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">Password</label>
					<input type="password" name="password"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
					@error('password')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Konfirmasi Password --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">Konfirmasi Password</label>
					<input type="password" name="password_confirmation"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
				</div>

				<button type="submit"
					class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
					Register
				</button>
			</form>

			<p class="mt-6 text-sm text-center text-gray-600">
				Sudah punya akun?
				<a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
			</p>
		</div>
	</div>
@endsection
