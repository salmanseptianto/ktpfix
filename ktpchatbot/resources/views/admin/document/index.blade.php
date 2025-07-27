@extends('layouts.admin')

@section('title', 'Permohonan Cetak')

@section('content')
	<div class="bg-white rounded-xl shadow p-6">
		<div class="flex justify-between items-center mb-4">
			<h2 class="text-3xl font-bold mb-4 text-gray-800">Daftar Permohonan Cetak</h2>
			<a href="{{ route('admin.antrian.index') }}"
				class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold shadow">
				+ Tambah Permohonan
			</a>
		</div>

		@if (session('success'))
			<div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
		@endif

		<div class="overflow-x-auto">
			<div class="flex gap-4 flex-wrap mb-4">
				<form method="GET" class="flex flex-wrap gap-2 items-end bg-gray-50 p-3 rounded shadow-sm">
					<div>
						<label class="block text-xs font-semibold mb-1">Tanggal</label>
						<input type="date" name="tanggal" value="{{ request('tanggal') }}" class="border rounded px-2 py-1">
					</div>
					<div>
						<label class="block text-xs font-semibold mb-1">Alasan Pencetakan</label>
						<select name="alasan" class="border rounded px-2 py-1">
							<option value="">Semua</option>
							<option value="baru" {{ request('alasan') == 'baru' ? 'selected' : '' }}>Baru</option>
							<option value="rusak" {{ request('alasan') == 'rusak' ? 'selected' : '' }}>Rusak</option>
							<option value="hilang" {{ request('alasan') == 'hilang' ? 'selected' : '' }}>Hilang</option>
							<option value="pembaruan data" {{ request('alasan') == 'pembaruan data' ? 'selected' : '' }}>Pembaruan Data
							</option>
						</select>
					</div>
					<button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded font-semibold">Filter</button>
					<a href="{{ route('admin.document.index') }}" class="px-3 py-1 rounded bg-gray-200 text-gray-700">Reset</a>
				</form>
				<form method="GET" action="{{ route('admin.document.printDocument') }}" target="_blank"
					class="flex flex-wrap gap-2 items-end bg-gray-50 p-3 rounded shadow-sm">
					<div>
						<label class="block text-xs font-semibold mb-1">Bulan</label>
						<select name="bulan" class="border rounded px-2 py-1">
							<option value="">Semua</option>
							@for ($m = 1; $m <= 12; $m++)
								<option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
									{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
								</option>
							@endfor
						</select>
					</div>
					<div>
						<label class="block text-xs font-semibold mb-1">Tahun</label>
						<select name="tahun" class="border rounded px-2 py-1">
							<option value="">Semua</option>
							@for ($y = now()->year; $y >= 2022; $y--)
								<option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}
								</option>
							@endfor
						</select>
					</div>
					<button type="submit" class="bg-red-600 text-white px-3 py-1 rounded font-semibold mt-6">
						Cetak PDF
					</button>
				</form>
			</div>
			<table class="min-w-full divide-y divide-gray-200 text-sm">
				<thead>
					<tr class="bg-gray-50 text-gray-700">
						<th class="px-4 py-2 text-left">#</th>
						<th class="px-4 py-2 text-left">Nama</th>
						<th class="px-4 py-2 text-left">NIK</th>
						<th class="px-4 py-2 text-left">Tanggal</th>
						<th class="px-4 py-2 text-left">Alasan Pencetakan</th>
						<th class="px-4 py-2 text-left">Status</th>
						<th class="px-4 py-2 text-left">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($requests as $i => $request)
						<tr class="border-b hover:bg-gray-50">
							<td class="px-4 py-2">{{ $i + 1 }}</td>
							<td class="px-4 py-2">{{ $request->user->name ?? '-' }}</td>
							<td class="px-4 py-2">{{ $request->nik }}</td>
							<td class="px-4 py-2">{{ $request->created_at->format('d-m-Y') }}</td>
							<td class="px-4 py-2">{{ ucfirst($request->alasan) }}</td>
							<td class="px-4 py-2">
								<span
									class="px-2 py-1 rounded text-xs
										@if ($request->status == 'dalam proses verifikasi') bg-yellow-100 text-yellow-700
										@elseif($request->status == 'dalam proses pencetakan') bg-blue-100 text-blue-700
										@elseif($request->status == 'sudah tercetak') bg-indigo-100 text-indigo-700
										@elseif($request->status == 'selesai pengambilan') bg-green-100 text-green-700
										@elseif($request->status == 'ditolak') bg-red-100 text-red-700 @endif">
									{{ ucfirst($request->status) }}
								</span>
							</td>
							<td class="px-4 py-2 flex gap-2">
								<a href="#"
									class="inline-block px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs btn-detail"
									data-id="{{ $request->id }}">
									Detail
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada permohonan cetak.</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<!-- Modal Detail -->
			<div id="modal-detail" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
				<div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
					<button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">&times;</button>
					<div id="modal-content">
						<!-- Konten detail akan dimuat di sini -->
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script>
		$(function() {
			// Tampilkan modal dan load detail via AJAX
			$('.btn-detail').on('click', function(e) {
				e.preventDefault();
				var id = $(this).data('id');
				$('#modal-content').html('<div class="text-center py-8">Loading...</div>');
				$('#modal-detail').removeClass('hidden');
				$.get("{{ route('admin.document.detailPop', ':id') }}".replace(':id', id), function(res) {
					$('#modal-content').html(res);

					// Bind AJAX submit untuk form update status
					$('#form-update-status').on('submit', function(e) {
						e.preventDefault();
						var form = $(this);
						var url = form.attr('action');
						var data = form.serialize();
						form.find('button[type=submit]').prop('disabled', true).text(
							'Menyimpan...');
						$.post(url, data, function(resp) {
							$('#status-update-msg').html(
								'<span class="text-green-600">Status berhasil diperbarui.</span>'
							);
							setTimeout(function() {
								$('#modal-detail').addClass('hidden');
								// Tampilkan pesan sukses di index
								showSuccess('Status berhasil diperbarui.');
								// Optionally, reload table data here via AJAX
								location
									.reload(); // reload agar tabel update status
							}, 800);
						}).fail(function(xhr) {
							let msg = 'Terjadi kesalahan.';
							if (xhr.responseJSON && xhr.responseJSON.message) {
								msg = xhr.responseJSON.message;
							}
							$('#status-update-msg').html(
								'<span class="text-red-600">' + msg + '</span>');
						}).always(function() {
							form.find('button[type=submit]').prop('disabled', false)
								.text('Simpan Perubahan');
						});
					});
				}).fail(function() {
					$('#modal-content').html(
						'<div class="text-red-600 text-center py-8">Gagal memuat detail.</div>');
				});
			});

			// Tutup modal
			$('#close-modal, #modal-detail').on('click', function(e) {
				if (e.target === this) {
					$('#modal-detail').addClass('hidden');
				}
			});

			// Fungsi untuk menampilkan pesan sukses di index
			window.showSuccess = function(msg) {
				if ($('#success-alert').length) {
					$('#success-alert').remove();
				}
				$('<div id="success-alert" class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 text-sm border border-green-200">' +
						msg + '</div>')
					.prependTo('.bg-white.rounded-xl.shadow.p-6');
				setTimeout(function() {
					$('#success-alert').fadeOut(500, function() {
						$(this).remove();
					});
				}, 2500);
			}
		});
	</script>
@endpush
