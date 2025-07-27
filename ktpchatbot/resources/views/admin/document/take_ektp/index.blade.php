@extends('layouts.admin')

@section('title', 'Pengambilan e-KTP')

@section('content')
	<div class="bg-white rounded-xl shadow p-6">
		<div class="flex justify-between items-center mb-4">
			<h2 class="text-3xl font-bold mb-4 text-gray-800">Daftar e-KTP Sudah Tercetak</h2>
		</div>
		@if (session('success'))
			<div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
		@endif
		<div class="overflow-x-auto">
			<table class="min-w-full divide-y divide-gray-200 text-sm">
				<thead>
					<tr class="bg-gray-50 text-gray-700">
						<th class="px-4 py-2 text-left">#</th>
						<th class="px-4 py-2 text-left">Nama Pemohon</th>
						<th class="px-4 py-2 text-left">NIK</th>
						<th class="px-4 py-2 text-left">Desa/Kelurahan</th>
						<th class="px-4 py-2 text-left">Nama Pengambil</th>
						<th class="px-4 py-2 text-left">Tanggal Pengambilan</th>
						<th class="px-4 py-2 text-left">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($ektp as $i => $request)
						<tr class="border-b hover:bg-gray-50">
							<td class="px-4 py-2">{{ $i + 1 }}</td>
							<td class="px-4 py-2">{{ $request->user->name ?? '-' }}</td>
							<td class="px-4 py-2">{{ $request->nik }}</td>
							<td class="px-4 py-2">{{ $request->desa_kelurahan }}</td>
							<td class="px-4 py-2">
								{{ optional($request->takeEktp)->nama_pengambil ?? 'Belum diambil' }}
							</td>
							<td class="px-4 py-2">
								{{ optional($request->takeEktp)->tanggal_pengambilan
								    ? \Carbon\Carbon::parse($request->takeEktp->tanggal_pengambilan)->format('d-m-Y')
								    : 'Belum diambil' }}
							</td>
							<td class="px-4 py-2">
								<a href="{{ route('admin.document.takeEktpShow', $request->id) }}"
									class="inline-block px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs">
									Input Pengambilan
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada e-KTP yang tercetak.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
@endsection
