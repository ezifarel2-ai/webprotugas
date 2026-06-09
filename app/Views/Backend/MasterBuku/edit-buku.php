<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li>Master Data Buku</li>
            <li class="active">Edit Data Buku</li>
        </ol>
    </div><!--/.row-->

    <div class="row">

        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-body">

                    <h3>Edit Buku</h3>

                    <hr />

                    <form action="<?= base_url('admin/update-data-buku');?>" method="post" enctype="multipart/form-data">

                        <div class="form-group col-md-12">
                            <label>Judul Buku</label>
                            <input type="text" class="form-control" name="judul_buku" value="<?= $data_buku['judul_buku'];?>" required>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label>Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" value="<?= $data_buku['pengarang'];?>" required>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label>Penerbit</label>
                            <input type="text" class="form-control" name="penerbit" value="<?= $data_buku['penerbit'];?>" required>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control" name="tahun" value="<?= $data_buku['tahun'];?>" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Jumlah Eksemplar</label>
                            <input type="number" class="form-control" name="jumlah_eksemplar" value="<?= $data_buku['jumlah_eksemplar'];?>" required>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label>Kategori Buku</label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori Buku --</option>
                                <?php foreach($data_kategori as $kategori) : ?>
                                <option value="<?= $kategori['id_kategori'];?>" <?= ($data_buku['id_kategori'] == $kategori['id_kategori']) ? 'selected' : '';?>><?= $kategori['nama_kategori'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="<?= $data_buku['keterangan'];?>">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Rak</label>
                            <select name="id_rak" class="form-control" required>
                                <option value="">-- Pilih Rak --</option>
                                <?php foreach($data_rak as $rak) : ?>
                                <option value="<?= $rak['id_rak'];?>" <?= ($data_buku['id_rak'] == $rak['id_rak']) ? 'selected' : '';?>><?= $rak['nama_rak'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Cover Buku</label>
                            <div style="margin-bottom: 10px;">
                                <?php if($data_buku['cover_buku'] != 'default.png' && $data_buku['cover_buku'] != '-' && $data_buku['cover_buku'] != '') : ?>
                                    <img id="preview-cover" src="<?= base_url('assets/images/cover/'.$data_buku['cover_buku']);?>" alt="Preview Cover" style="width: 100%; max-width: 400px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                <?php else : ?>
                                    <img id="preview-cover" src="" alt="Preview Cover" style="display:none; width: 100%; max-width: 400px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" name="cover_buku" id="cover_buku" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
                            <small class="text-muted"><i>Format file yang diizinkan : jpg, jpeg, png Maksimal ukuran 1 MB (Biarkan kosong jika tidak diubah)</i></small>
                        </div>

                        <div class="form-group col-md-12">
                            <label>E-Book</label>
                            <div style="margin-bottom: 10px;">
                                <?php if($data_buku['e_book'] != '-' && $data_buku['e_book'] != '') : ?>
                                    <iframe id="preview-ebook" src="<?= base_url('assets/ebooks/'.$data_buku['e_book']);?>" style="width: 100%; height: 400px; border: 1px solid #ddd;"></iframe>
                                <?php else : ?>
                                    <iframe id="preview-ebook" src="" style="display:none; width: 100%; height: 400px; border: 1px solid #ddd;"></iframe>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" name="e_book" id="e_book" accept=".pdf" onchange="previewPdf(event)">
                            <small class="text-muted"><i>Format file yang diizinkan : pdf Maksimal ukuran 10 MB (Biarkan kosong jika tidak diubah)</i></small>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="<?= base_url('admin/master-data-buku');?>" class="btn btn-default">Kembali</a>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div><!--/.row-->

</div><!--/.main-->

<script type="text/javascript">
    function previewImage(event) {
        var input = event.target;
        var preview = document.getElementById('preview-cover');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "";
            preview.style.display = 'none';
        }
    }

    function previewPdf(event) {
        var input = event.target;
        var preview = document.getElementById('preview-ebook');
        
        if (input.files && input.files[0]) {
            var fileURL = URL.createObjectURL(input.files[0]);
            preview.src = fileURL;
            preview.style.display = 'block';
        } else {
            preview.src = "";
            preview.style.display = 'none';
        }
    }
</script>
