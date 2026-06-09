<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function mahasiswa() 
    {
        $uri = service('uri');
        $nama = $uri->getSegment(4);
        $nim = $uri->getSegment(5);
        $kelas = $uri->getSegment(6);

        $data['nama'] = $nama;
        $data['nim'] = $nim;
        $data['kelas'] = $kelas;

        return view('segment_views', $data);
    }
}
