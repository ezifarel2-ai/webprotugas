<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li class="active">Master Data Buku</li>
        </ol>
    </div><!--/.row-->

    <div class="row">

        <div class="col-lg-12">

            <div class="panel panel-default">

                <div class="panel-body">

                    <h3>
                        Data Buku

                        <a href="<?= base_url('admin/input-data-buku');?>">
                            <button type="button" class="btn btn-sm btn-primary pull-right">
                                Input Data Buku
                            </button>
                        </a>
                    </h3>

                    <hr />

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
                                <th data-sortable="true">Cover Buku</th>
                                <th data-sortable="true">Judul Buku</th>
                                <th data-sortable="true">Pengarang</th>
                                <th data-sortable="true">Penerbit</th>
                                <th data-sortable="true">Tahun</th>
                                <th data-sortable="true">Jumlah Eksemplar</th>
                                <th data-sortable="true">Kategori Buku</th>
                                <th data-sortable="true">Keterangan</th>
                                <th data-sortable="true">Rak</th>
                                <th data-sortable="true">Opsi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                                $no = 0;
                                foreach($data_buku as $data){
                            ?>

                            <tr>
                                <td><?= $no=$no+1;?></td>
                                <td>
                                    <?php if($data['cover_buku'] != 'default.png' && $data['cover_buku'] != '-' && $data['cover_buku'] != '') : ?>
                                        <img src="<?= base_url('assets/images/cover/'.$data['cover_buku']);?>" alt="Cover" style="width:50px; height:50px; object-fit:cover;">
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $data['judul_buku'];?></td>
                                <td><?= $data['pengarang'];?></td>
                                <td><?= $data['penerbit'];?></td>
                                <td><?= $data['tahun'];?></td>
                                <td><?= $data['jumlah_eksemplar'];?></td>
                                <td><?= $data['nama_kategori'];?></td>
                                <td><?= $data['keterangan'];?></td>
                                <td><?= $data['nama_rak'];?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit-data-buku/'.sha1($data['id_buku']));?>">
                                        <button type="button" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</button>
                                    </a>
                                    <a href="javascript:void(0)" onclick="doDelete('<?= sha1($data['id_buku']);?>')">
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                    </a>
                                </td>
                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div><!--/.row-->

</div><!--/.main-->

<script type="text/javascript">

    function doDelete(idDelete){
        swal({
            title : "Hapus Data Buku?",
            text : "Data ini akan terhapus secara permanen!!",
            icon : "warning",
            buttons : true,
            dangerMode: true,
        }).then((willDelete) => {
            if(willDelete){
                window.location.href = '<?= base_url('admin/hapus-data-buku');?>/' + idDelete;
            }
        });
    }

</script>
