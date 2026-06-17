<?php

namespace App\Controllers;
//Load models
use App\Models\M_Admin;
use App\Models\M_Anggota;
use App\Models\M_Kategori;
use App\Models\M_Rak;
use App\Models\M_Buku;
use App\Models\M_Peminjaman;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class Admin extends BaseController
{
    public function login()
    {
        return view('Backend/Login/login');
    }

    public function dashboard()
    {
        if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            session()->setFlashdata('error','Silahkan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        else{
            echo view('Backend/Template/header');
            echo view('Backend/Template/sidebar');
            echo view('Backend/Login/dashboard-admin');
            echo view('Backend/Template/footer');
        }
    }


    public function autentikasi(){
        $modelAdmin = new M_Admin; // proses inisiasi model
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $cekUsername = $modelAdmin->getDataAdmin(['username_admin' => $username,'is_delete_admin' => '0'])->getNumRows();
        if($cekUsername == 0){
            session()->setFlashdata('error','Username Tidak Ditemukan!');
            return redirect()->back();
        } 
        else {
            $dataUser = $modelAdmin->getDataAdmin(['username_admin' => $username,'is_delete_admin' => '0'])->getRowArray();
            $passwordUser = $dataUser['password_admin'];

            $verifikasiPassword = password_verify($password, $passwordUser);

            if(!$verifikasiPassword){
                session()->setFlashdata('error','Password Tidak Sesuai!');
                return redirect()->back();
            } 
            else {
                $dataSession = [
                    'ses_id'   => $dataUser['id_admin'],
                    'ses_user' => $dataUser['nama_admin'],
                    'ses_level'=> $dataUser['akses_level']
                ];

                session()->set($dataSession);
                session()->setFlashdata('success','Login Berhasil!');
                return redirect()->to(base_url('admin/dashboard-admin'));
            }
        }
        
        }
        public function logout(){
            session()->remove('ses_id');
            session()->remove('ses_user');
            session()->remove('ses_level');
            session()->remove('info', 'Anda telah keluar dari sistem!');
            return redirect()->to(base_url('admin/login-admin'));
            
        }

        public function input_data_admin(){
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                echo view('Backend/Template/header');
                echo view('Backend/Template/sidebar');
                echo view('Backend/MasterAdmin/input-admin');
                echo view('Backend/Template/footer');
            }
}

        public function simpan_data_admin(){
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelAdmin = new M_Admin; // inisiasi
        
                $nama = $this->request->getPost('nama');
                $username = $this->request->getPost('username');
                $level = $this->request->getPost('level');
        
                $cekUname = $modelAdmin->getDataAdmin(['username_admin' => $username])->getNumRows();
                if($cekUname > 0){
                    session()->setFlashdata('error', 'Username sudah digunakan!!');
                    return redirect()->back();
                }
                else{
                    $hasil = $modelAdmin->autoNumber()->getRowArray();
                if(!$hasil){
                    $id = "ADM001";
                }
                else{
                    $kode = $hasil['id_admin'];
                    $noUrut = (int) substr($kode, -3);
                    $noUrut++;
                    $id = "ADM".sprintf("%03s", $noUrut);
                }
        
                $dataSimpan = [
                    'id_admin'       => $id,
                    'nama_admin'     => $nama,
                    'username_admin' => $username,
                    'password_admin' => password_hash('pass_admin', PASSWORD_DEFAULT),
                    'akses_level'    => $level,
                    'is_delete_admin'=> '0',
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s')
                ];
        
                $modelAdmin->saveDataAdmin($dataSimpan);
                session()->setFlashdata('success', 'Data Admin Berhasil Ditambahkan!!');
                return redirect()->to(base_url('admin/master-data-admin'));
            
                }
            }
        }

        public function master_data_admin(){

            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
            
                return redirect()->to(base_url('admin/login-admin'));
            
            }
            else{
            
                $modelAdmin = new M_Admin; // inisiasi
            
                $uri = service('uri');
                $pages = $uri->getSegment(2);
            
                $dataUser = $modelAdmin->getDataAdmin([
                    'is_delete_admin' => '0',
                    'akses_level !=' => '1'
                ])->getResultArray();
            
                $data['pages'] = $pages;
                $data['data_user'] = $dataUser;
            
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterAdmin/master-data-admin', $data);
                echo view('Backend/Template/footer', $data);
            
            }
            
            }

            public function edit_data_admin()
            {
                $uri = service('uri');
                $idEdit = $uri->getSegment(3);
                $modelAdmin = new M_Admin;
                // Mengambil data admin dari table admin di database berdasarkan parameter yang dikirimkan
                $dataAdmin = $modelAdmin->getDataAdmin(['sha1(id_admin)' => $idEdit])->getRowArray();
                session()->set(['idUpdate' => $dataAdmin['id_admin']]);
    
                $page = $uri->getSegment(2);
    
                $data['page'] = $page;
                $data['web_title'] = "Edit Data Admin";
                $data['data_admin'] = $dataAdmin; // mengirim array data admin ke view
    
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterAdmin/edit-admin', $data);
                echo view('Backend/Template/footer', $data);
            }
    
    
            public function update_data_admin()
            {
                $modelAdmin = new M_Admin;
            
                $idUpdate = session()->get('idUpdate');
                $nama = $this->request->getPost('nama');
                $level = $this->request->getPost('level');
            
                if($nama=="" or $level==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $dataUpdate = [
                        'nama_admin' => $nama,
                        'akses_level' => $level,
                        'updated_at' => date("Y-m-d H:i:s")
                    ];
                    $whereUpdate = ['id_admin' => $idUpdate];
                
                    $modelAdmin->updateDataAdmin($dataUpdate, $whereUpdate);
                    session()->remove('idUpdate');
                    session()->setFlashdata('success', 'Data Admin Berhasil Diperbaharui!');
                    return redirect()->to(base_url('admin/master-data-admin'));
                }
            }
    
            public function hapus_data_admin()
            {
                $modelAdmin = new M_Admin;
            
                $uri = service('uri');
                $idHapus = $uri->getSegment(3);
            
                $dataUpdate = [
                    'is_delete_admin' => '1',
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $whereUpdate = ['sha1(id_admin)' => $idHapus];
                $modelAdmin->updateDataAdmin($dataUpdate, $whereUpdate);
                session()->setFlashdata('success', 'Data Admin Berhasil Dihapus!');
                return redirect()->to(base_url('admin/master-data-admin'));
            }
            public function master_data_anggota()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelAnggota = new M_Anggota;
    
                $uri = service('uri');
                $pages = $uri->getSegment(2);
    
                $dataAnggota = $modelAnggota->getDataAnggota([
                    'is_delete_anggota' => '0'
                ])->getResultArray();
    
                $data['pages'] = $pages;
                $data['data_anggota'] = $dataAnggota;
    
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterAnggota/master-data-anggota', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function input_data_anggota()
    {
        if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            session()->setFlashdata('error','Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        else{
            echo view('Backend/Template/header');
            echo view('Backend/Template/sidebar');
            echo view('Backend/MasterAnggota/input-anggota');
            echo view('Backend/Template/footer');
        }
    }

    public function simpan_data_anggota()
    {
        if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            session()->setFlashdata('error','Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        else{
            $modelAnggota = new M_Anggota;

            $nama = $this->request->getPost('nama');
            $jenisKelamin = $this->request->getPost('jenis_kelamin');
            $noTlp = $this->request->getPost('no_tlp');
            $alamat = $this->request->getPost('alamat');
            $email = $this->request->getPost('email');

            if($nama=="" or $jenisKelamin=="" or $noTlp=="" or $alamat=="" or $email==""){
                session()->setFlashdata('error','Isian tidak boleh kosong!!');
                return redirect()->back();
            }
            else{
                $cekEmail = $modelAnggota->getDataAnggota([
                    'email' => $email
                ])->getNumRows();

                if($cekEmail > 0){
                    session()->setFlashdata('error','Email sudah digunakan!!');
                    return redirect()->back();
                }
                else{
                    $hasil = $modelAnggota->autoNumber()->getRowArray();

                    if(!$hasil){
                        $id = "AGT001";
                    }
                    else{
                        $kode = $hasil['id_anggota'];
                        $noUrut = (int) substr($kode, -3);
                        $noUrut++;
                        $id = "AGT".sprintf("%03s", $noUrut);
                    }

                    $dataSimpan = [
                        'id_anggota' => $id,
                        'nama_anggota' => $nama,
                        'jenis_kelamin' => $jenisKelamin,
                        'no_tlp' => $noTlp,
                        'alamat' => $alamat,
                        'email' => $email,
                        'password_anggota' => password_hash('pass_anggota', PASSWORD_DEFAULT),
                        'is_delete_anggota' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $modelAnggota->saveDataAnggota($dataSimpan);

                    session()->setFlashdata('success', 'Data Anggota Berhasil Ditambahkan!!');
                    return redirect()->to(base_url('admin/master-data-anggota'));
                }
            }
        }
    }

    public function edit_data_anggota()
    {
        if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            session()->setFlashdata('error','Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        else{
            $uri = service('uri');
            $idEdit = $uri->getSegment(3);

            $modelAnggota = new M_Anggota;

            $dataAnggota = $modelAnggota->getDataAnggota([
                'sha1(id_anggota)' => $idEdit
            ])->getRowArray();

            session()->set(['idUpdateAnggota' => $dataAnggota['id_anggota']]);

            $page = $uri->getSegment(2);

            $data['page'] = $page;
            $data['web_title'] = "Edit Data Anggota";
            $data['data_anggota'] = $dataAnggota;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/MasterAnggota/edit-anggota', $data);
            echo view('Backend/Template/footer', $data);
        }
    }

    public function update_data_anggota()
    {
        $modelAnggota = new M_Anggota;

        $idUpdate = session()->get('idUpdateAnggota');

        $nama = $this->request->getPost('nama');
        $jenisKelamin = $this->request->getPost('jenis_kelamin');
        $noTlp = $this->request->getPost('no_tlp');
        $alamat = $this->request->getPost('alamat');
        $email = $this->request->getPost('email');

        if($nama=="" or $jenisKelamin=="" or $noTlp=="" or $alamat=="" or $email==""){
            session()->setFlashdata('error','Isian tidak boleh kosong!!');
            return redirect()->back();
        }
        else{
            $dataUpdate = [
                'nama_anggota' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'no_tlp' => $noTlp,
                'alamat' => $alamat,
                'email' => $email,
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $whereUpdate = ['id_anggota' => $idUpdate];

            $modelAnggota->updateDataAnggota($dataUpdate, $whereUpdate);

            session()->remove('idUpdateAnggota');

            session()->setFlashdata('success', 'Data Anggota Berhasil Diperbaharui!');
            return redirect()->to(base_url('admin/master-data-anggota'));
        }
    }
    public function hapus_data_anggota()
    {
        $modelAnggota = new M_Anggota;

        $uri = service('uri');
        $idHapus = $uri->getSegment(3);

        $dataUpdate = [
            'is_delete_anggota' => '1',
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $whereUpdate = ['sha1(id_anggota)' => $idHapus];

        $modelAnggota->updateDataAnggota($dataUpdate, $whereUpdate);

        session()->setFlashdata('success', 'Data Anggota Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }

        public function master_data_kategori()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelKategori = new M_Kategori;
    
                $uri = service('uri');
                $pages = $uri->getSegment(2);
    
                $dataKategori = $modelKategori->getDataKategori([
                    'is_delete_kategori' => '0'
                ])->getResultArray();
    
                $data['pages'] = $pages;
                $data['data_kategori'] = $dataKategori;
    
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterKategori/master-data-kategori', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function input_data_kategori()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                echo view('Backend/Template/header');
                echo view('Backend/Template/sidebar');
                echo view('Backend/MasterKategori/input-kategori');
                echo view('Backend/Template/footer');
            }
        }

        public function simpan_data_kategori()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelKategori = new M_Kategori;

                $nama = $this->request->getPost('nama');

                if($nama==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $cekNama = $modelKategori->getDataKategori([
                        'nama_kategori' => $nama,
                        'is_delete_kategori' => '0'
                    ])->getNumRows();

                    if($cekNama > 0){
                        session()->setFlashdata('error','Nama Kategori sudah digunakan!!');
                        return redirect()->back();
                    }
                    else{
                        $hasil = $modelKategori->autoNumber()->getRowArray();

                        if(!$hasil){
                            $id = "KTG001";
                        }
                        else{
                            $kode = $hasil['id_kategori'];
                            $noUrut = (int) substr($kode, -3);
                            $noUrut++;
                            $id = "KTG".sprintf("%03s", $noUrut);
                        }

                        $dataSimpan = [
                            'id_kategori' => $id,
                            'nama_kategori' => $nama,
                            'is_delete_kategori' => '0',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $modelKategori->saveDataKategori($dataSimpan);

                        session()->setFlashdata('success', 'Data Kategori Berhasil Ditambahkan!!');
                        return redirect()->to(base_url('admin/master-data-kategori'));
                    }
                }
            }
        }

        public function edit_data_kategori()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $uri = service('uri');
                $idEdit = $uri->getSegment(3);

                $modelKategori = new M_Kategori;

                $dataKategori = $modelKategori->getDataKategori([
                    'sha1(id_kategori)' => $idEdit
                ])->getRowArray();

                session()->set(['idUpdateKategori' => $dataKategori['id_kategori']]);

                $page = $uri->getSegment(2);

                $data['page'] = $page;
                $data['web_title'] = "Edit Data Kategori";
                $data['data_kategori'] = $dataKategori;

                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterKategori/edit-kategori', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function update_data_kategori()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelKategori = new M_Kategori;

                $idUpdate = session()->get('idUpdateKategori');
                $nama = $this->request->getPost('nama');

                if($nama==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $cekNama = $modelKategori->getDataKategori([
                        'nama_kategori' => $nama,
                        'is_delete_kategori' => '0',
                        'id_kategori !=' => $idUpdate
                    ])->getNumRows();

                    if($cekNama > 0){
                        session()->setFlashdata('error','Nama Kategori sudah digunakan!!');
                        return redirect()->back();
                    }
                    else{
                        $dataUpdate = [
                            'nama_kategori' => $nama,
                            'updated_at' => date("Y-m-d H:i:s")
                        ];

                        $whereUpdate = ['id_kategori' => $idUpdate];

                        $modelKategori->updateDataKategori($dataUpdate, $whereUpdate);

                        session()->remove('idUpdateKategori');

                        session()->setFlashdata('success', 'Data Kategori Berhasil Diperbaharui!');
                        return redirect()->to(base_url('admin/master-data-kategori'));
                    }
                }
            }
        }

        public function hapus_data_kategori()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelKategori = new M_Kategori;

                $uri = service('uri');
                $idHapus = $uri->getSegment(3);

                $dataUpdate = [
                    'is_delete_kategori' => '1',
                    'updated_at' => date("Y-m-d H:i:s")
                ];

                $whereUpdate = ['sha1(id_kategori)' => $idHapus];

                $modelKategori->updateDataKategori($dataUpdate, $whereUpdate);

                session()->setFlashdata('success', 'Data Kategori Berhasil Dihapus!');
                return redirect()->to(base_url('admin/master-data-kategori'));
            }
        }

        public function master_data_rak()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelRak = new M_Rak;
    
                $uri = service('uri');
                $pages = $uri->getSegment(2);
    
                $dataRak = $modelRak->getDataRak([
                    'is_delete_rak' => '0'
                ])->getResultArray();
    
                $data['pages'] = $pages;
                $data['data_rak'] = $dataRak;
    
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterRak/master-data-rak', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function input_data_rak()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                echo view('Backend/Template/header');
                echo view('Backend/Template/sidebar');
                echo view('Backend/MasterRak/input-rak');
                echo view('Backend/Template/footer');
            }
        }

        public function simpan_data_rak()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelRak = new M_Rak;

                $nama = $this->request->getPost('nama');

                if($nama==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $cekNama = $modelRak->getDataRak([
                        'nama_rak' => $nama,
                        'is_delete_rak' => '0'
                    ])->getNumRows();

                    if($cekNama > 0){
                        session()->setFlashdata('error','Nama Rak sudah digunakan!!');
                        return redirect()->back();
                    }
                    else{
                        $hasil = $modelRak->autoNumber()->getRowArray();

                        if(!$hasil){
                            $id = "RAK001";
                        }
                        else{
                            $kode = $hasil['id_rak'];
                            $noUrut = (int) substr($kode, -3);
                            $noUrut++;
                            $id = "RAK".sprintf("%03s", $noUrut);
                        }

                        $dataSimpan = [
                            'id_rak' => $id,
                            'nama_rak' => $nama,
                            'is_delete_rak' => '0',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $modelRak->saveDataRak($dataSimpan);

                        session()->setFlashdata('success', 'Data Rak Berhasil Ditambahkan!!');
                        return redirect()->to(base_url('admin/master-data-rak'));
                    }
                }
            }
        }

        public function edit_data_rak()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $uri = service('uri');
                $idEdit = $uri->getSegment(3);

                $modelRak = new M_Rak;

                $dataRak = $modelRak->getDataRak([
                    'sha1(id_rak)' => $idEdit
                ])->getRowArray();

                session()->set(['idUpdateRak' => $dataRak['id_rak']]);

                $page = $uri->getSegment(2);

                $data['page'] = $page;
                $data['web_title'] = "Edit Data Rak";
                $data['data_rak'] = $dataRak;

                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterRak/edit-rak', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function update_data_rak()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelRak = new M_Rak;

                $idUpdate = session()->get('idUpdateRak');
                $nama = $this->request->getPost('nama');

                if($nama==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $cekNama = $modelRak->getDataRak([
                        'nama_rak' => $nama,
                        'is_delete_rak' => '0',
                        'id_rak !=' => $idUpdate
                    ])->getNumRows();

                    if($cekNama > 0){
                        session()->setFlashdata('error','Nama Rak sudah digunakan!!');
                        return redirect()->back();
                    }
                    else{
                        $dataUpdate = [
                            'nama_rak' => $nama,
                            'updated_at' => date("Y-m-d H:i:s")
                        ];

                        $whereUpdate = ['id_rak' => $idUpdate];

                        $modelRak->updateDataRak($dataUpdate, $whereUpdate);

                        session()->remove('idUpdateRak');

                        session()->setFlashdata('success', 'Data Rak Berhasil Diperbaharui!');
                        return redirect()->to(base_url('admin/master-data-rak'));
                    }
                }
            }
        }

        public function hapus_data_rak()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelRak = new M_Rak;

                $uri = service('uri');
                $idHapus = $uri->getSegment(3);

                $dataUpdate = [
                    'is_delete_rak' => '1',
                    'updated_at' => date("Y-m-d H:i:s")
                ];

                $whereUpdate = ['sha1(id_rak)' => $idHapus];

                $modelRak->updateDataRak($dataUpdate, $whereUpdate);

                session()->setFlashdata('success', 'Data Rak Berhasil Dihapus!');
                return redirect()->to(base_url('admin/master-data-rak'));
            }
        }

        public function master_data_buku()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelBuku = new M_Buku;
    
                $uri = service('uri');
                $pages = $uri->getSegment(2);
    
                $dataBuku = $modelBuku->getDataBuku([
                    'is_delete_buku' => '0'
                ])->getResultArray();
    
                $data['pages'] = $pages;
                $data['data_buku'] = $dataBuku;
    
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterBuku/master-data-buku', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function input_data_buku()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelKategori = new M_Kategori;
                $modelRak = new M_Rak;

                $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
                $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();

                echo view('Backend/Template/header');
                echo view('Backend/Template/sidebar');
                echo view('Backend/MasterBuku/input-buku', $data);
                echo view('Backend/Template/footer');
            }
        }

        public function simpan_data_buku()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelBuku = new M_Buku;

                $judul = $this->request->getPost('judul_buku');
                $pengarang = $this->request->getPost('pengarang');
                $penerbit = $this->request->getPost('penerbit');
                $tahun = $this->request->getPost('tahun');
                $jumlah_eksemplar = $this->request->getPost('jumlah_eksemplar');
                $id_kategori = $this->request->getPost('id_kategori');
                $keterangan = $this->request->getPost('keterangan');
                $id_rak = $this->request->getPost('id_rak');

                if($judul=="" || $pengarang==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $hasil = $modelBuku->autoNumber()->getRowArray();

                    if(!$hasil){
                        $id = "BKU001";
                    }
                    else{
                        $kode = $hasil['id_buku'];
                        $noUrut = (int) substr($kode, -3);
                        $noUrut++;
                        $id = "BKU".sprintf("%03s", $noUrut);
                    }

                    $coverBuku = $this->request->getFile('cover_buku');
                    $namaCover = 'default.png';
                    if($coverBuku && $coverBuku->isValid() && ! $coverBuku->hasMoved()){
                        $namaCover = $coverBuku->getRandomName();
                        $coverBuku->move(FCPATH . 'assets/images/cover', $namaCover);
                    }
    
                    $eBook = $this->request->getFile('e_book');
                    $namaEBook = '-';
                    if($eBook && $eBook->isValid() && ! $eBook->hasMoved()){
                        $namaEBook = $eBook->getRandomName();
                        $eBook->move(FCPATH . 'assets/ebooks', $namaEBook);
                    }

                    $dataSimpan = [
                        'id_buku' => $id,
                        'judul_buku' => $judul,
                        'pengarang' => $pengarang,
                        'penerbit' => $penerbit,
                        'tahun' => $tahun,
                        'jumlah_eksemplar' => $jumlah_eksemplar,
                        'id_kategori' => $id_kategori,
                        'keterangan' => $keterangan,
                        'id_rak' => $id_rak,
                        'cover_buku' => $namaCover,
                        'e_book' => $namaEBook,
                        'is_delete_buku' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $modelBuku->saveDataBuku($dataSimpan);

                    session()->setFlashdata('success', 'Data Buku Berhasil Ditambahkan!!');
                    return redirect()->to(base_url('admin/master-data-buku'));
                }
            }
        }

        public function edit_data_buku()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $uri = service('uri');
                $idEdit = $uri->getSegment(3);

                $modelBuku = new M_Buku;
                $modelKategori = new M_Kategori;
                $modelRak = new M_Rak;

                $dataBuku = $modelBuku->getDataBuku([
                    'sha1(id_buku)' => $idEdit
                ])->getRowArray();

                session()->set(['idUpdateBuku' => $dataBuku['id_buku']]);

                $page = $uri->getSegment(2);

                $data['page'] = $page;
                $data['web_title'] = "Edit Data Buku";
                $data['data_buku'] = $dataBuku;
                $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
                $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();

                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/MasterBuku/edit-buku', $data);
                echo view('Backend/Template/footer', $data);
            }
        }

        public function update_data_buku()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelBuku = new M_Buku;

                $idUpdate = session()->get('idUpdateBuku');
                $judul = $this->request->getPost('judul_buku');
                $pengarang = $this->request->getPost('pengarang');
                $penerbit = $this->request->getPost('penerbit');
                $tahun = $this->request->getPost('tahun');
                $jumlah_eksemplar = $this->request->getPost('jumlah_eksemplar');
                $id_kategori = $this->request->getPost('id_kategori');
                $keterangan = $this->request->getPost('keterangan');
                $id_rak = $this->request->getPost('id_rak');

                if($judul=="" || $pengarang==""){
                    session()->setFlashdata('error','Isian tidak boleh kosong!!');
                    return redirect()->back();
                }
                else{
                    $dataUpdate = [
                        'judul_buku' => $judul,
                        'pengarang' => $pengarang,
                        'penerbit' => $penerbit,
                        'tahun' => $tahun,
                        'jumlah_eksemplar' => $jumlah_eksemplar,
                        'id_kategori' => $id_kategori,
                        'keterangan' => $keterangan,
                        'id_rak' => $id_rak,
                        'updated_at' => date("Y-m-d H:i:s")
                    ];

                    $coverBuku = $this->request->getFile('cover_buku');
                    if($coverBuku && $coverBuku->isValid() && ! $coverBuku->hasMoved()){
                        $namaCover = $coverBuku->getRandomName();
                        $coverBuku->move(FCPATH . 'assets/images/cover', $namaCover);
                        $dataUpdate['cover_buku'] = $namaCover;
                    }
    
                    $eBook = $this->request->getFile('e_book');
                    if($eBook && $eBook->isValid() && ! $eBook->hasMoved()){
                        $namaEBook = $eBook->getRandomName();
                        $eBook->move(FCPATH . 'assets/ebooks', $namaEBook);
                        $dataUpdate['e_book'] = $namaEBook;
                    }

                    $whereUpdate = ['id_buku' => $idUpdate];

                    $modelBuku->updateDataBuku($dataUpdate, $whereUpdate);

                    session()->remove('idUpdateBuku');

                    session()->setFlashdata('success', 'Data Buku Berhasil Diperbaharui!');
                    return redirect()->to(base_url('admin/master-data-buku'));
                }
            }
        }

        public function hapus_data_buku()
        {
            if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
                session()->setFlashdata('error','Silakan login terlebih dahulu!');
                return redirect()->to(base_url('admin/login-admin'));
            }
            else{
                $modelBuku = new M_Buku;

                $uri = service('uri');
                $idHapus = $uri->getSegment(3);

                $dataUpdate = [
                    'is_delete_buku' => '1',
                    'updated_at' => date("Y-m-d H:i:s")
                ];

                $whereUpdate = ['sha1(id_buku)' => $idHapus];

                $modelBuku->updateDataBuku($dataUpdate, $whereUpdate);

                session()->setFlashdata('success', 'Data Buku Berhasil Dihapus!');
                return redirect()->to(base_url('admin/master-data-buku'));
            }
        }
        
        public function peminjaman_step1()
        {
            $uri = service('uri');
            $page = $uri->getSegment(2);
            $data['page'] = $page;
            $data['web_title'] = "Transaksi Peminjaman";
            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/Transaksi/peminjaman-step-1', $data);
            echo view('Backend/Template/footer', $data);
        }

        public function peminjaman_step2()
        {
            $modelAnggota = new M_Anggota;
            $modelBuku = new M_Buku;
            $modelPeminjaman = new M_Peminjaman;
        
            $uri = service('uri');
            $page = $uri->getSegment(2);
        
            if($this->request->getPost('id_anggota')){
                $idAnggota = $this->request->getPost('id_anggota');
                session()->set(['idAgt' => $idAnggota]);
            }
            else{
                $idAnggota = session()->get('idAgt');
            }
        
            $cekPeminjaman = $modelPeminjaman->getDataPeminjaman([
                'id_anggota' => $idAnggota,
                'status_transaksi' => "Berjalan"
            ])->getNumRows();
            
            if($cekPeminjaman > 0){
                session()->setFlashdata('error',
                'Transaksi Tidak Dapat Dilakukan, Masih Ada Transaksi Peminjaman yang Belum Diselesaikan!!');
        ?>
        <script>
            history.go(-1);
        </script>
        <?php
            }
            else{
                $dataAnggota = $modelAnggota->getDataAnggota([
                    'id_anggota' => $idAnggota
                ])->getRowArray();
                $dataBuku = $modelBuku->getDataBuku(['is_delete_buku' => '0'])->getResultArray();
                
                $jumlahTemp = $modelPeminjaman->getDataTemp([
                    'id_anggota' => $idAnggota
                ])->getNumRows();
                
                $data['jumlahTemp'] = $jumlahTemp;
                
                // Mengambil data keseluruhan buku dari table buku di database
                
                $dataTemp = $modelPeminjaman->getDataTempJoin([
                    'tbl_temp_peminjaman.id_anggota' => $idAnggota
                ])->getResultArray();
                
                $data['page'] = $page;
                $data['web_title'] = "Transaksi Peminjaman";
                $data['dataAnggota'] = $dataAnggota;
                $data['dataBuku'] = $dataBuku;
                $data['dataTemp'] = $dataTemp;
                
                echo view('Backend/Template/header', $data);
                echo view('Backend/Template/sidebar', $data);
                echo view('Backend/Transaksi/peminjaman-step-2', $data);
                echo view('Backend/Template/footer', $data);
            }
        }
        public function simpan_temp_pinjam()
        {
            $modelPeminjaman = new M_Peminjaman;
            $modelBuku = new M_Buku;
                
            $uri = service('uri');
            $idBuku = $uri->getSegment(3);
                
            $dataBuku = $modelBuku->getDataBuku([
                'sha1(id_buku)' => $idBuku
            ])->getRowArray();
            
            $adaTemp = $modelPeminjaman->getDataTemp([
                'sha1(id_buku)' => $idBuku
            ])->getNumRows();
            
            $adaBerjalan = $modelPeminjaman->getDataPeminjaman([
                'id_anggota' => session()->get('idAgt'),
                'status_transaksi' => "Berjalan"
            ])->getNumRows();
            
            if($adaTemp){
                session()->setFlashdata('error',
                'Satu Anggota Hanya Bisa Meminjam 1 Buku dengan Judul Yang Sama!');
        ?>
        <script>
            history.go(-1);
        </script>
        <?php
            }
            elseif($adaBerjalan){
                session()->setFlashdata('error',
                'Masih ada transaksi peminjaman yang belum diselesaikan, silakan selesaikan peminjaman sebelumnya terlebih dahulu!');
        ?>
        <script>
            history.go(-1);
        </script>
        <?php
            }
            else{
                $dataSimpanTemp = [
                    'id_anggota' => session()->get('idAgt'),
                    'id_buku' => $dataBuku['id_buku'],
                    'jumlah_temp' => '1'
                ];
            
                $modelPeminjaman->saveDataTemp($dataSimpanTemp);
            
                $stok = $dataBuku['jumlah_eksemplar'] - 1;
            
                $dataUpdate = [
                    'jumlah_eksemplar' => $stok
                ];
            
                $modelBuku->updateDataBuku($dataUpdate,[
                    'sha1(id_buku)' => $idBuku
                ]);
        ?>
        <script>
            document.location = "<?= base_url('admin/peminjaman-step-2');?>";
        </script>
        <?php
            }
        }

        public function hapus_peminjaman()
        {
            $modelPeminjaman = new M_Peminjaman;
            $modelBuku = new M_Buku;

            $uri = service('uri');
            $idBuku = $uri->getSegment(3);

            $dataBuku = $modelBuku->getDataBuku([
                'sha1(id_buku)' => $idBuku
            ])->getRowArray();

            $stok = $dataBuku['jumlah_eksemplar'] + 1;

            $dataUpdate = [
                'jumlah_eksemplar' => $stok
            ];

            $modelBuku->updateDataBuku($dataUpdate,[
                'sha1(id_buku)' => $idBuku
            ]);

            $modelPeminjaman->hapusDataTemp([
                'sha1(id_buku)' => $idBuku
            ]);
        ?>
        <script>
            document.location = "<?= base_url('admin/peminjaman-step-2');?>";
        </script>
        <?php
        }

        public function simpan_transaksi_peminjaman()
        {
            $modelPeminjaman = new M_Peminjaman;

            $idPeminjaman = date("ymdHis");
            $time_sekarang = time();
            $kembali = date("Y-m-d", strtotime("+7 days", $time_sekarang));
            $jumlahPinjam = $modelPeminjaman->getDataTemp([
                'id_anggota' => session()->get('idAgt')
            ])->getNumRows();

            $dataQR = $idPeminjaman;
            $labelQR = $idPeminjaman;

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($dataQR)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                ->logoPath(FCPATH.'Assets/logo-ubsi.png')
                ->logoResizeToWidth(50)
                ->logoPunchoutBackground(true)
                ->labelText($labelQR)
                ->labelFont(new NotoSans(20))
                ->labelAlignment(LabelAlignment::Center)
                ->validateResult(false)
                ->build();

            // Directly output the QR code
            header('Content-Type: '.$result->getMimeType());

            // Save it to a file
            $namaQR = "qr_".$idPeminjaman.".png";
            $result->saveToFile(FCPATH.'Assets/qr_code/'.$namaQR);

            $dataSimpan = [
                'no_peminjaman' => $idPeminjaman,
                'id_anggota' => session()->get('idAgt'),
                'tgl_pinjam' => date("Y-m-d"),
                'total_pinjam' => $jumlahPinjam,
                'id_admin' => '',
                'status_transaksi' => "Berjalan",
                'status_ambil_buku' => "Sudah Diambil"
            ];

            $modelPeminjaman->saveDataPeminjaman($dataSimpan);

            $dataTemp = $modelPeminjaman->getDataTemp([
                'id_anggota' => session()->get('idAgt')
            ])->getResultArray();

            foreach($dataTemp as $sementara){
                $simpanDetail = [
                    'no_peminjaman' => $idPeminjaman,
                    'id_buku' => $sementara['id_buku'],
                    'status_pinjam' => "Sedang Dipinjam",
                    'perpanjangan' => "2",
                    'tgl_kembali' => $kembali
                ];

                $modelPeminjaman->saveDataDetail($simpanDetail);
            }

            $modelPeminjaman->hapusDataTemp([
                'id_anggota' => session()->get('idAgt')
            ]);

            session()->remove('idAgt');
            session()->setFlashdata('success','Data Peminjaman Buku Berhasil Disimpan!');
            ?>
            <script>
                document.location = "<?= base_url('admin/data-transaksi-peminjaman');?>";
            </script>
            <?php
        }
        public function data_transaksi_peminjaman()
        {
            $modelPeminjaman = new M_Peminjaman;
        
            $uri = service('uri');
            $page = $uri->getSegment(2);
        
            $dataPeminjaman = $modelPeminjaman
                ->getDataPeminjamanJoin()
                ->getResultArray();
        
            $data['page'] = $page;
            $data['web_title'] = "Data Transaksi Peminjaman";
            $data['dataPeminjaman'] = $dataPeminjaman;
        
            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/Transaksi/data-peminjaman', $data);
            echo view('Backend/Template/footer', $data);
        }
        public function detail_peminjaman()
        {
            $modelPeminjaman = new M_Peminjaman;
        
            $uri = service('uri');
            $page = $uri->getSegment(2);
        
            $noPeminjaman = $uri->getSegment(3);
        
            $dataHeader = $modelPeminjaman
                ->getDataPeminjamanJoin([
                    'sha1(no_peminjaman)' => $noPeminjaman
                ])
                ->getRowArray();
                
            $dataDetail = $modelPeminjaman
                ->getDataDetailPeminjamanJoin([
                    'sha1(no_peminjaman)' => $noPeminjaman
                ])
                ->getResultArray();
                
            $data['page'] = $page;
            $data['web_title'] = "Detail Peminjaman";
            $data['dataHeader'] = $dataHeader;
            $data['dataDetail'] = $dataDetail;
                
            echo view('Backend/Template/header',$data);
            echo view('Backend/Template/sidebar',$data);
            echo view('Backend/Transaksi/detail-peminjaman',$data);
            echo view('Backend/Template/footer',$data);
        }
        public function laporan_peminjaman()
        {
            $modelPeminjaman = new M_Peminjaman;
        
            $uri = service('uri');
            $page = $uri->getSegment(2);
        
            $dataPeminjaman = $modelPeminjaman->getDataPeminjamanJoin()->getResultArray();
        
            $data['page'] = $page;
            $data['web_title'] = "Laporan Peminjaman";
            $data['dataPeminjaman'] = $dataPeminjaman;
        
            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/Transaksi/laporan-peminjaman', $data);
            echo view('Backend/Template/footer', $data);
        }
        public function cetak_laporan_peminjaman()
        {
            $modelPeminjaman = new M_Peminjaman;
        
            $dataPeminjaman = $modelPeminjaman->getDataPeminjamanJoin()->getResultArray();
        
            $data['web_title'] = "Cetak Laporan Peminjaman";
            $data['dataPeminjaman'] = $dataPeminjaman;
        
            echo view('Backend/Transaksi/cetak-laporan-peminjaman', $data);
        }
        public function pengembalian_buku()
        {
            $modelPeminjaman = new M_Peminjaman;
        
            $uri = service('uri');
            $page = $uri->getSegment(2);
        
            $dataPeminjaman = $modelPeminjaman->getDataPeminjamanJoin([
                'tbl_peminjaman.status_transaksi' => "Berjalan"
            ])->getResultArray();
            
            $data['page'] = $page;
            $data['web_title'] = "Pengembalian Buku";
            $data['dataPeminjaman'] = $dataPeminjaman;
            
            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/Transaksi/pengembalian-buku', $data);
            echo view('Backend/Template/footer', $data);
        }
        public function proses_pengembalian()
{
    $modelPeminjaman = new M_Peminjaman;
    $modelBuku = new M_Buku;

    $uri = service('uri');
    $noPeminjaman = $uri->getSegment(3);

    $dataPeminjaman = $modelPeminjaman->getDataPeminjaman([
        'sha1(no_peminjaman)' => $noPeminjaman,
        'status_transaksi' => "Berjalan"
    ])->getRowArray();

    if(!$dataPeminjaman){
        session()->setFlashdata('error', 'Data peminjaman tidak ditemukan atau transaksi sudah selesai!');
        return redirect()->to(base_url('admin/pengembalian-buku'));
    }

    $dataDetail = $modelPeminjaman->getDataDetailPeminjaman([
        'sha1(no_peminjaman)' => $noPeminjaman
    ])->getResultArray();

    foreach($dataDetail as $detail){
        $dataBuku = $modelBuku->getDataBuku([
            'id_buku' => $detail['id_buku']
        ])->getRowArray();

        if($dataBuku){
            $stok = $dataBuku['jumlah_eksemplar'] + 1;

            $dataUpdateBuku = [
                'jumlah_eksemplar' => $stok
            ];

            $modelBuku->updateDataBuku($dataUpdateBuku, [
                'id_buku' => $detail['id_buku']
            ]);
        }

        $dataUpdateDetail = [
            'status_pinjam' => "Sudah Dikembalikan"
        ];

        $modelPeminjaman->updateDataDetail($dataUpdateDetail, [
            'no_peminjaman' => $dataPeminjaman['no_peminjaman'],
            'id_buku' => $detail['id_buku']
        ]);
    }

    $dataUpdatePeminjaman = [
        'status_transaksi' => "Selesai"
    ];

    $modelPeminjaman->updateDataPeminjaman($dataUpdatePeminjaman, [
        'sha1(no_peminjaman)' => $noPeminjaman
    ]);

    session()->setFlashdata('success', 'Transaksi peminjaman berhasil diselesaikan!');
    return redirect()->to(base_url('admin/pengembalian-buku'));
}

}


            
