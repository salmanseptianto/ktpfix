@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
	<div class="mb-8">
		<h1 class="text-2xl font-bold text-gray-800 mb-1">Dashboard Panel Admin</h1>
		<p class="text-gray-600">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>! Berikut ringkasan
			sistem.</p>
	</div>

	{{-- Ringkasan Statistik --}}
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
		{{-- Card: Permintaan Dokumen --}}
		<a href="{{ route('admin.document.index') }}"
			class="group bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl shadow hover:shadow-lg transition-all duration-200 p-6 flex items-center gap-5 hover:scale-[1.03]">
			<div class="bg-blue-600/10 text-blue-600 rounded-xl p-4 flex items-center justify-center">
				<svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m0-5V3m-8 4v4a4 4 0 004 4h4" />
				</svg>
			</div>
			<div>
				<div class="text-sm text-blue-700 font-semibold mb-1 group-hover:text-blue-900 transition">Permohonan Cetak</div>
				<div class="text-4xl font-bold text-blue-700 group-hover:text-blue-900 transition">{{ $documentRequest }}</div>
			</div>
		</a>

		{{-- Card: Blangko Tersedia --}}
		<a href="{{ route('admin.blangko.index') }}"
			class="group bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl shadow hover:shadow-lg transition-all duration-200 p-6 flex items-center gap-5 hover:scale-[1.03]">
			<div class="bg-green-600/10 text-green-600 rounded-xl p-4 flex items-center justify-center">
				<svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<circle cx="12" cy="12" r="10" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M12 8v8" />
				</svg>
			</div>
			<div>
				<div class="text-sm text-green-700 font-semibold mb-1 group-hover:text-green-900 transition">Blangko Tersedia</div>
				<div class="text-4xl font-bold text-green-700 group-hover:text-green-900 transition">{{ $blangkoStock }}</div>
			</div>
		</a>

		{{-- Card: Jumlah User --}}
		<a href="{{ route('admin.users.index') }}"
			class="group bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl shadow hover:shadow-lg transition-all duration-200 p-6 flex items-center gap-5 hover:scale-[1.03]">
			<div class="bg-purple-600/10 text-purple-600 rounded-xl p-4 flex items-center justify-center">
				<svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round"
						d="M12 11c1.66 0 3-1.34 3-3S13.66 5 12 5 9 6.34 9 8s1.34 3 3 3zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
				</svg>
			</div>
			<div>
				<div class="text-sm text-purple-700 font-semibold mb-1 group-hover:text-purple-900 transition">Jumlah User</div>
				<div class="text-4xl font-bold text-purple-700 group-hover:text-purple-900 transition">{{ $userCount ?? '-' }}</div>
			</div>
		</a>
	</div>

	{{-- Statistik Visual --}}
	<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
		<div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
			<h2 class="text-lg font-semibold mb-4 text-gray-800">Grafik Pengajuan Dokumen {{ $year }}</h2>
			<canvas id="chart-pengajuan"></canvas>
		</div>
		<div class="w-6/12 mx-auto bg-white rounded-2xl shadow border border-gray-100 p-6">
			<h2 class="text-lg font-semibold mb-4 text-gray-800">Pie Alasan Pengajuan Bulan
				{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</h2>
			<canvas id="chart-alasan"></canvas>
		</div>
	</div>

	{{-- Info tambahan --}}
	<div class="mt-10 bg-white p-6 rounded-2xl shadow border border-gray-100">
		<h2 class="text-lg font-semibold mb-2 text-gray-800">Informasi</h2>
		<p class="text-sm text-gray-600 leading-relaxed">
			Panel ini digunakan oleh petugas admin untuk memproses permintaan pencetakan KTP dari user,
			mengelola stok blangko, serta mencatat riwayat pendaftaran termasuk untuk lansia yang datang langsung.
		</p>
	</div>
@endsection

@push('scripts')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<style>
		/* Custom flatpickr ala shadcn */
		.flatpickr-calendar {
			border-radius: 1rem !important;
			box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.08), 0 1.5px 4px 0 rgba(0, 0, 0, 0.03) !important;
			border: 1px solid #e5e7eb !important;
			margin-top: 0.5rem;
		}

		.flatpickr-day.today {
			background: #2563eb !important;
			color: #fff !important;
			border-radius: 0.5rem !important;
		}

		.flatpickr-day.selected,
		.flatpickr-day.startRange,
		.flatpickr-day.endRange {
			background: #1e40af !important;
			color: #fff !important;
			border-radius: 0.5rem !important;
		}

		.flatpickr-day:hover {
			background: #dbeafe !important;
			color: #1e40af !important;
		}

		.flatpickr-months .flatpickr-month {
			border-radius: 0.5rem !important;
		}

		.flatpickr-weekdays {
			border-radius: 0.5rem !important;
		}
	</style>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script>
		// Tampilkan tanggal hari ini di atas kalender
		const today = new Date();
		const options = {
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric'
		};
		document.addEventListener('DOMContentLoaded', function() {
			document.getElementById('today-date').textContent = today.toLocaleDateString('id-ID', options);
			flatpickr("#dashboard-calendar", {
				inline: true,
				locale: "id",
				defaultDate: today,
				dateFormat: "Y-m-d",
				// disable input, hanya kalender
				clickOpens: false,
				allowInput: false,
			});
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		// Grafik Pengajuan Dokumen per Bulan
		const ctxLine = document.getElementById('chart-pengajuan').getContext('2d');
		new Chart(ctxLine, {
			type: 'line',
			data: {
				labels: [
					'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
					'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
				],
				datasets: [{
					label: 'Jumlah Pengajuan',
					data: @json($monthlyData),
					borderColor: '#2563eb',
					backgroundColor: 'rgba(37,99,235,0.1)',
					tension: 0.4,
					fill: true,
					pointRadius: 5,
					pointBackgroundColor: '#2563eb'
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							stepSize: 1
						}
					}
				}
			}
		});

		// Pie Chart Alasan Pengajuan
		const ctxPie = document.getElementById('chart-alasan').getContext('2d');
		new Chart(ctxPie, {
			type: 'pie',
			data: {
				labels: ['Baru', 'Rusak', 'Hilang', 'Pembaruan Data'],
				datasets: [{
					data: [
						{{ $pieData['baru'] ?? 0 }},
						{{ $pieData['rusak'] ?? 0 }},
						{{ $pieData['hilang'] ?? 0 }},
						{{ $pieData['pembaruan data'] ?? 0 }}
					],
					backgroundColor: [
						'#2563eb', '#22c55e', '#f59e42', '#a855f7'
					],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'bottom',
						labels: {
							padding: 30 // jarak antar legend dan chart
						}
					}
				},
				layout: {
					padding: {
						top: 30 // jarak antara chart dan legend
					}
				}
			}
		});
	</script>
@endpush
