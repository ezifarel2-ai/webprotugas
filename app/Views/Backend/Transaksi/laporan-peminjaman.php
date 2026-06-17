<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li>Laporan</li>
            <li class="active">Laporan Peminjaman</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-body">
                    <h3>Laporan Peminjaman Buku</h3>
                    <hr />
                    <a href="<?= base_url('admin/cetak-laporan-peminjaman');?>" target="_blank">
    <button type="button" class="btn btn-success">
        <span class="glyphicon glyphicon-print"></span> Cetak Laporan
    </button>
</a>

<br><br>

                    <table data-toggle="table"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-search="true"
                           data-select-item-name="toolbar1"
                           data-pagination="true"
                           data-sort-name="name"
                           data-sort-order="desc">

                        <thead>
                            <tr>
                                <th data-sortable="true">No</th>
                                <th data-sortable="true">No Peminjaman</th>
                                <th data-sortable="true">Nama Anggota</th>
                                <th data-sortable="true">Tanggal Pinjam</th>
                                <th data-sortable="true">Total Pinjam</th>
                                <th data-sortable="true">Status Transaksi</th>
                                <th data-sortable="true">Status Ambil Buku</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 0;
                            foreach($dataPeminjaman as $data){
                            ?>
                            <tr>
                                <td><?php echo $no=$no+1;?></td>
                                <td><?php echo $data['no_peminjaman'];?></td>
                                <td><?php echo $data['nama_anggota'];?></td>
                                <td><?php echo $data['tgl_pinjam'];?></td>
                                <td><?php echo $data['total_pinjam'];?></td>
                                <td><?php echo $data['status_transaksi'];?></td>
                                <td><?php echo $data['status_ambil_buku'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </div><!--/.row-->

</div>