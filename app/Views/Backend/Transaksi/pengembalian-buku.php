<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li>Transaksi</li>
            <li class="active">Pengembalian Buku</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-body">
                    <h3>Pengembalian Buku</h3>
                    <hr />

                    <?php if(session()->getFlashdata('success')){ ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success'); ?>
                        </div>
                    <?php } ?>

                    <?php if(session()->getFlashdata('error')){ ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php } ?>

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
                                <th data-sortable="true">Opsi</th>
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
                                <td>
                                    <span class="label label-warning">
                                        <?php echo $data['status_transaksi'];?>
                                    </span>
                                </td>
                                <td><?php echo $data['status_ambil_buku'];?></td>
                                <td>
                                    <a href="#" onclick="doReturn('<?= sha1($data['no_peminjaman']);?>')">
                                        <button type="button" class="btn btn-success">
                                            <span class="glyphicon glyphicon-ok"></span>
                                            Kembalikan Buku
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(empty($dataPeminjaman)){ ?>
                            <tr>
                                <td colspan="8" style="text-align:center;">
                                    Tidak ada transaksi peminjaman yang sedang berjalan.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </div><!--/.row-->

</div>

<script type="text/javascript">
function doReturn(idReturn) {
    swal({
        title: "Selesaikan Peminjaman?",
        text: "Pastikan buku sudah benar-benar dikembalikan oleh anggota.",
        icon: "warning",
        buttons: true,
        dangerMode: false,
    })
    .then(ok => {
        if (ok) {
            window.location.href = '<?= base_url() ?>/admin/proses-pengembalian/' + idReturn;
        } else {
            $(this).removeAttr('disabled')
        }
    })
}
</script>