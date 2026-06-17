<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Transaksi</li>
            <li class="active">Detail Peminjaman</li>
        </ol>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">

            <h3>Data Peminjaman</h3>
            <hr>

            <p>
                <b>No Peminjaman :</b>
                <?= $dataHeader['no_peminjaman']; ?>
            </p>

            <p>
                <b>Nama Anggota :</b>
                <?= $dataHeader['nama_anggota']; ?>
            </p>

            <p>
                <b>Tanggal Pinjam :</b>
                <?= $dataHeader['tgl_pinjam']; ?>
            </p>

            <p>
                <b>Status :</b>
                <?= $dataHeader['status_transaksi']; ?>
            </p>

            <hr>

            <h3>Daftar Buku Dipinjam</h3>

            <table data-toggle="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Tanggal Kembali</th>
                    <th>Status Pinjam</th>
                </tr>
                </thead>

                <tbody>

                <?php
                $no=0;
                foreach($dataDetail as $data){
                ?>

                <tr>
                    <td><?= $no=$no+1; ?></td>
                    <td><?= $data['judul_buku']; ?></td>
                    <td><?= $data['pengarang']; ?></td>
                    <td><?= $data['tgl_kembali']; ?></td>
                    <td><?= $data['status_pinjam']; ?></td>
                </tr>

                <?php } ?>

                </tbody>
            </table>

        </div>
    </div>

</div>