<!DOCTYPE html>
<html>
<head>
    <title><?= $web_title; ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            width: 95%;
            margin: auto;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h2 {
            margin: 0;
            padding: 0;
        }

        .judul p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 7px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .ttd {
            width: 250px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }

        .no-print {
            margin-bottom: 20px;
        }

        .btn-print {
            padding: 8px 15px;
            background: #337ab7;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-back {
            padding: 8px 15px;
            background: #d9534f;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            Cetak
        </button>

        <a href="<?= base_url('admin/laporan-peminjaman');?>" class="btn-back">
            Kembali
        </a>
    </div>

    <div class="judul">
        <h2>LAPORAN PEMINJAMAN BUKU</h2>
        <p>Perpustakaan Digital</p>
        <p>Tanggal Cetak: <?= date('d-m-Y'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>No Peminjaman</th>
                <th>Nama Anggota</th>
                <th>Tanggal Pinjam</th>
                <th>Total Pinjam</th>
                <th>Status Transaksi</th>
                <th>Status Ambil Buku</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 0;
            foreach($dataPeminjaman as $data){
            ?>
            <tr>
                <td class="text-center"><?= $no=$no+1; ?></td>
                <td><?= $data['no_peminjaman']; ?></td>
                <td><?= $data['nama_anggota']; ?></td>
                <td><?= date('d-m-Y', strtotime($data['tgl_pinjam'])); ?></td>
                <td class="text-center"><?= $data['total_pinjam']; ?></td>
                <td><?= $data['status_transaksi']; ?></td>
                <td><?= $data['status_ambil_buku']; ?></td>
            </tr>
            <?php } ?>

            <?php if(empty($dataPeminjaman)){ ?>
            <tr>
                <td colspan="7" class="text-center">
                    Data peminjaman belum tersedia.
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Mengetahui,</p>
        <p>Admin Perpustakaan</p>
        <br><br><br>
        <p>________________________</p>
    </div>

</div>

<script>
    window.print();
</script>

</body>
</html>