@extends('layouts.admin')

@section('content')
	<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg">
		<h2 class="text-xl font-semibold mb-4">Detail Permohonan Cetak</h2>

		{{-- Alert sukses/error --}}
		@if (session('success'))
			<div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 text-sm border border-green-200">
				{{ session('success') }}
			</div>
		@endif
		@if (session('error'))
			<div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 text-sm border border-red-200">
				{{ session('error') }}
			</div>
		@endif

		<table class="w-full mb-6 text-sm">
			<tbody>
				<tr>
					<td class="py-2 font-semibold w-48">Nama</td>
					<td class="py-2">{{ $request->user->name ?? '-' }}</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">NIK</td>
					<td class="py-2">{{ $request->nik }}</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">No KK</td>
					<td class="py-2">{{ $request->kk ?? '-' }}</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">Desa/Kelurahan</td>
					<td class="py-2">{{ $request->desa_kelurahan ?? '-' }}</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">Alasan Pencetakan</td>
					<td class="py-2">{{ $request->alasan ?? '-' }}</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">Status</td>
					<td class="py-2">
						<span
							class="px-2 py-1 rounded text-xs
							@if ($request->status == 'dalam proses verifikasi') bg-yellow-100 text-yellow-700
							@elseif($request->status == 'dalam proses pencetakan') bg-blue-100 text-blue-700
							@elseif($request->status == 'sudah tercetak') bg-indigo-100 text-indigo-700
							@elseif($request->status == 'selesai pengambilan') bg-green-100 text-green-700
							@elseif($request->status == 'ditolak') bg-red-100 text-red-700 @endif">
							{{ ucfirst($request->status) }}
						</span>
						@if ($request->status == 'ditolak' && !empty($request->alasan_tolak))
							<div class="mt-2 text-red-600 text-xs">
								<strong>Alasan Penolakan:</strong> {{ $request->alasan_tolak }}
							</div>
						@endif
					</td>
				</tr>
			</tbody>
		</table>

		<table class="w-full mb-8 text-sm">
			<tbody>
				<tr>
					<td class="py-2 font-semibold w-48">Kartu Keluarga (KK)</td>
					<td class="py-2">
						@if ($request->upload && $request->upload->file_kk)
							<a href="{{ asset('storage/' . $request->upload->file_kk) }}" target="_blank"
								class="text-blue-600 underline">Lihat File</a>
						@else
							<span class="text-gray-500 italic">Tidak tersedia</span>
						@endif
					</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">KTP Lama</td>
					<td class="py-2">
						@if ($request->upload && $request->upload->file_ktp_lama)
							<a href="{{ asset('storage/' . $request->upload->file_ktp_lama) }}" target="_blank"
								class="text-blue-600 underline">Lihat File</a>
						@else
							<span class="text-gray-500 italic">Tidak tersedia</span>
						@endif
					</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">Surat Kehilangan</td>
					<td class="py-2">
						@if ($request->upload && $request->upload->file_surat_kehilangan)
							<a href="{{ asset('storage/' . $request->upload->file_surat_kehilangan) }}" target="_blank"
								class="text-blue-600 underline">Lihat File</a>
						@else
							<span class="text-gray-500 italic">Tidak tersedia</span>
						@endif
					</td>
				</tr>
				<tr>
					<td class="py-2 font-semibold">Swafoto</td>
					<td class="py-2">
						@if ($request->upload && $request->upload->file_swafoto)
							<a href="{{ asset('storage/' . $request->upload->file_swafoto) }}" target="_blank"
								class="text-blue-600 underline">Lihat File</a>
						@else
							<span class="text-gray-500 italic">Tidak tersedia</span>
						@endif
					</td>
				</tr>
			</tbody>
		</table>

		<form action="{{ route('admin.document.updateStatus', $request->id) }}" method="POST" class="space-y-4">
			@csrf
			<div>
				<label class="block text-sm font-medium">Ubah Status</label>
				<select id="status-select" name="status" class="w-full border rounded px-3 py-2"
					@if ($request->status == 'selesai pengambilan' || $request->status == 'ditolak') disabled @endif>
					<option value="dalam proses verifikasi" {{ $request->status == 'dalam proses verifikasi' ? 'selected' : '' }}>Dalam
						Proses Verifikasi</option>
					<option value="dalam proses pencetakan" {{ $request->status == 'dalam proses pencetakan' ? 'selected' : '' }}>Dalam
						Proses Pencetakan</option>
					<option value="sudah tercetak" {{ $request->status == 'sudah tercetak' ? 'selected' : '' }}>Sudah Tercetak</option>
					<option value="selesai pengambilan" {{ $request->status == 'selesai pengambilan' ? 'selected' : '' }}>Selesai
						Pengambilan</option>
					<option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
				</select>
			</div>

			{{-- Container Alasan Penolakan --}}
			<div id="alasan-penolakan-container" style="display: {{ $request->status == 'ditolak' ? 'block' : 'none' }};">
				<label class="block text-sm font-medium">Alasan Penolakan</label>
				<select name="alasan_ditolak" class="w-full border rounded px-3 py-2"
					@if ($request->status == 'selesai pengambilan') disabled @endif>
					<option value="">-- Pilih Alasan --</option>
					<option value="informasi tidak sesuai"
						{{ $request->alasan_ditolak == 'informasi tidak sesuai' ? 'selected' : '' }}>Informasi tidak sesuai</option>
					<option value="kualitas gambar terlalu rendah"
						{{ $request->alasan_ditolak == 'kualitas gambar terlalu rendah' ? 'selected' : '' }}>Kualitas gambar terlalu
						rendah</option>
					<option value="dokumen tidak terbaca jelas"
						{{ $request->alasan_ditolak == 'dokumen tidak terbaca jelas' ? 'selected' : '' }}>Dokumen tidak terbaca jelas
					</option>
					<option value="dokumen tidak valid" {{ $request->alasan_ditolak == 'dokumen tidak valid' ? 'selected' : '' }}>
						Dokumen tidak valid</option>
					<option value="persyaratan belum lengkap"
						{{ $request->alasan_ditolak == 'persyaratan belum lengkap' ? 'selected' : '' }}>Persyaratan belum lengkap</option>
					<option value="tidak memenuhi syarat" {{ $request->alasan_ditolak == 'tidak memenuhi syarat' ? 'selected' : '' }}>
						Tidak memenuhi syarat</option>
				</select>
			</div>

			<button type="submit"
				class="px-4 py-2 rounded transition
				@if ($request->status == 'selesai pengambilan' || $request->status == 'ditolak') bg-gray-400 text-gray-100 cursor-not-allowed
				@else
            			bg-blue-600 text-white hover:bg-blue-700 @endif"
				@if ($request->status == 'selesai pengambilan' || $request->status == 'ditolak') disabled @endif>
				Simpan Perubahan
			</button>
		</form>
	</div>
@endsection
