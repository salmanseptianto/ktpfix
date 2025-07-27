<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'Dashboard User')</title>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link rel="icon" type="image/png" href="{{ asset('images/logo_header.png') }}">
</head>

<body class="bg-gray-100 min-h-screen font-sans">
	<header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-gray-200 shadow-sm">
		<div class="max-w-3xl mx-auto flex justify-between items-center px-6 py-4">
			<div class="text-xl font-extrabold text-blue-700 tracking-tight">
				<a href="{{ route('user.index') }}" class="hover:underline">Sistem Antrian KTP-El</a>
			</div>
			<form method="POST" action="{{ route('user.logout') }}">
				@csrf
				<button
					class="bg-blue-50 border border-blue-200 text-blue-700 font-semibold px-4 py-1.5 rounded-lg shadow-sm hover:bg-blue-100 transition text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
					Logout
				</button>
			</form>
		</div>
	</header>

	<main class="flex justify-center items-center min-h-[80vh] px-2">
		<div class="w-full max-w-2xl">
			@yield('content')
		</div>
	</main>

	@stack('scripts')
</body>

</html>
