<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<title>Berita Acara Serah Terima Blangko</title>
	<style>
		body {
			font-family: "Times New Roman", Times, serif;
			font-size: 14px;
			color: #000;
		}

		.header {
			text-align: center;
			/* line-height: 1.5; */
			margin-bottom: 5px;
			position: relative;
		}

		.header img.logo {
			position: absolute;
			top: 0;
			left: 0;
			width: 90px;
			height: auto;
		}

		.judul {
			font-weight: bold;
			text-align: center;
			margin-top: 10px;
		}

		.nomor {
			text-align: center;
			margin-bottom: 20px;
		}

		.content {
			text-align: justify;
		}

		.table-identitas,
		.table-identitas td {
			border: none !important;
			padding: 2px 4px;
			font-size: 14px;
			text-align: left;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 10px;
			font-size: 12px;
		}

		th,
		td {
			border: 1px solid #000;
			padding: 5px;
			text-align: center;
		}

		.signature {
			width: 100%;
			margin-top: 40px;
			font-size: 12px;
		}

		.signature td {
			border: none;
			padding-top: 20px;
			text-align: center;
		}
	</style>
</head>

<body>

	<div class="header" style="text-align: center; margin-bottom: 10px;">
		<img src="{{ public_path('images/logo_header.png') }}" alt="Logo Brebes" class="logo"
			style="width: 80px; position: absolute; top: 5px; left: 30px;">

		<div style="margin-left: 90px;">
			<div style="font-size: 14px; line-height: 1.5;">PEMERINTAH KABUPATEN BREBES</div>
			<div style="font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">DINAS KEPENDUDUKAN
				DAN <br> PENCATATAN SIPIL</div>
			<div style="font-size: 14px; line-height: 1.5;">Jalan P. Diponegoro Nomor 150 Telepon (0283)671322 Faksimile
				(0283)671322</div>
		</div>

		{{-- Garis pembatas --}}
		<div style="margin-top: 10px; border-bottom: 1px solid black;"></div>
		<div style="margin-top: 2px; border-bottom: 5px solid black;"></div>
	</div>

	<div class="judul">
		BERITA ACARA SERAH TERIMA (BAST) <br>
		PENYALURAN BARANG PERSEDIAAN
	</div>
	<div class="nomor">Nomor: BAST/{{ now()->format('His') }}/VI/{{ now()->year }}</div>

	<p class="content">
		Pada hari ini {{ \Carbon\Carbon::parse($blangko->tanggal)->translatedFormat('l') }}
		tanggal {{ \Carbon\Carbon::parse($blangko->tanggal)->format('d') }} bulan
		{{ \Carbon\Carbon::parse($blangko->tanggal)->translatedFormat('F Y') }},
		bertempat di Dinas Kependudukan dan Pencatatan Sipil Kabupaten Brebes,
		yang bertanda tangan dibawah ini :
	</p>

	<table class="table-identitas" style="width: 100%; font-size: 14px; margin-top: 10px;">
		<tr>
			<td style="width: 5%;">I</td>
			<td style="width: 25%;">Nama</td>
			<td style="width: 3%;">:</td>
			<td>{{ $blangko->petugas_nama }}</td>
		</tr>
		<tr>
			<td></td>
			<td>NIP</td>
			<td>:</td>
			<td>{{ $blangko->petugas_nip }}</td>
		</tr>
		<tr>
			<td></td>
			<td>Pangkat/Gol</td>
			<td>:</td>
			<td>{{ $blangko->petugas_pangkat }}</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4" style="padding-top: 5px;">
				Dalam hal ini bertindak sebagai Pengurus Barang Dinas Kependudukan dan Pencatatan Sipil Kabupaten Brebes,
				selanjutnya sebagai <strong>PIHAK PERTAMA</strong>
			</td>
		</tr>
		<tr>
			<td style="padding-top: 10px;">II</td>
			<td style="padding-top: 10px;">Nama</td>
			<td style="padding-top: 10px;">:</td>
			<td style="padding-top: 10px;">{{ $blangko->penerima_nama ?? '-' }}</td>
		</tr>
		<tr>
			<td></td>
			<td>NIP</td>
			<td>:</td>
			<td>{{ $blangko->penerima_nip ?? '-' }}</td>
		</tr>
		<tr>
			<td></td>
			<td>Pangkat/Gol</td>
			<td>:</td>
			<td>{{ $blangko->penerima_pangkat ?? '-' }}</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4" style="padding-top: 5px;">
				Dalam hal ini bertindak sebagai penerima BMD berupa barang persediaan, selanjutnya sebagai <strong>PIHAK
					KEDUA</strong>
			</td>
		</tr>
	</table>

	<p class="content" style="margin-top: 15px;">
		<strong>PIHAK PERTAMA</strong> telah melakukan serah terima BMD pada <strong>PIHAK KEDUA</strong> berupa barang
		persediaan,
		berdasarkan Surat Perintah Penyaluran Barang (SPPB) Tanggal
		{{ \Carbon\Carbon::parse($blangko->tanggal)->translatedFormat('d F Y') }}
		Nomor : {{ $blangko->nomor_sppb ?? 'SPPB/0001/2023' }} dengan rincian sebagai berikut :
	</p>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Spesifikasi</th>
				<th>Jumlah</th>
				<th>Satuan</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>{{ $blangko->kode_barang }}</td>
				<td>BLANGKO KTP-EL</td>
				<td>KTP elektronik</td>
				<td>{{ $blangko->restok }}</td>
				<td>Buah</td>
				<td>Pencetakan KTP</td>
			</tr>
		</tbody>
	</table>

	<p class="content">
		Berita acara serah terima ini dibuat sebagai bukti pengeluaran barang persediaan
	</p>

	<table class="signature">
		<tr>
			<td width="50%"><strong>PIHAK KEDUA</strong></td>
			<td width="50%"><strong>PIHAK PERTAMA</strong></td>
		</tr>
		<tr>
			<td height="40px"></td>
			<td></td>
		</tr>
		<tr>
			<td>
				<div style="line-height: 1.5; margin: 0; padding: 0;">
					<strong>{{ $blangko->penerima_nama ?? '-' }}</strong><br>
					NIP: {{ $blangko->penerima_nip ?? '-' }}
				</div>
			</td>
			<td>
				<div style="line-height: 1.5; margin: 0; padding: 0;">
					<strong>{{ $blangko->petugas_nama }}</strong><br>
					NIP: {{ $blangko->petugas_nip }}
				</div>
			</td>
		</tr>
	</table>

</body>

</html>
