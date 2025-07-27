@extends('layouts.auth')

@section('content')
	<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
		<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-md">
			<h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>

			@if (session('success'))
				<div class="mb-4 text-green-600 text-sm text-center">
					{{ session('success') }}
				</div>
			@endif

			@if ($errors->any())
				<div class="mb-4 text-red-600 text-sm text-center">
					{{ $errors->first() }}
				</div>
			@endif

			<form action="{{ route('login') }}" method="POST" class="space-y-4">
				@csrf

				{{-- Username (Email/NIK) --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">Username</label>
					<input type="text" name="username" value="{{ old('username') }}"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
				</div>

				{{-- Password --}}
				<div>
					<label class="block mb-1 text-sm text-gray-600">Password</label>
					<input type="password" name="password"
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
				</div>

				<button type="submit"
					class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
					Login
				</button>
			</form>

			<p class="mt-6 text-sm text-center text-gray-600">
				Belum punya akun?
				<a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register sebagai User</a>
			</p>
		</div>
	</div>
@endsection
