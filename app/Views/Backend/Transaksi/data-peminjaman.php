<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Transaksi</li>
            <li class="active">Data Peminjaman</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-body">
                    <h3>Transaksi Peminjaman Buku</h3>
                    <hr>

                    <table data-toggle="table"
                           data-search="true"
                           data-pagination="true">

                        <thead>
                        <tr>
                            <th>No Peminjaman</th>
                            <th>Nama Anggota</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Total Buku Yang Dipinjam</th>
                            <th>Status Transaksi</th>
                            <th>Status Ambil Buku</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php foreach($dataPeminjaman as $data){ ?>

                        <tr>
                            <td><?= $data['no_peminjaman']; ?></td>
                            <td><?= $data['nama_anggota']; ?></td>
                            <td><?= $data['tgl_pinjam']; ?></td>
                            <td><?= $data['total_pinjam']; ?></td>

                            <td>
                                <span class="label label-warning">
                                    <?= $data['status_transaksi']; ?>
                                </span>
                            </td>

                            <td><?= $data['status_ambil_buku']; ?></td>

                            <td>
                                <a href="<?= base_url('admin/detail-peminjaman')."/".sha1($data['no_peminjaman']); ?>">
                                    <button class="btn btn-primary btn-sm">
                                        Lihat Detail
                                    </button>
                                </a>
                            </td>
                        </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </div>

</div>