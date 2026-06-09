<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Buku extends Model
{
    protected $table = 'tbl_buku';

    public function getDataBuku($where = false)
    {
        if ($where === false) {
            return $this->db->table($this->table)
                            ->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_buku.id_kategori', 'left')
                            ->join('tbl_rak', 'tbl_rak.id_rak = tbl_buku.id_rak', 'left')
                            ->get();
        } else {
            return $this->db->table($this->table)
                            ->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_buku.id_kategori', 'left')
                            ->join('tbl_rak', 'tbl_rak.id_rak = tbl_buku.id_rak', 'left')
                            ->where($where)
                            ->get();
        }
    }

    public function saveDataBuku($data)
    {
        return $this->db->table($this->table)
                        ->insert($data);
    }

    public function updateDataBuku($data, $where)
    {
        return $this->db->table($this->table)
                        ->where($where)
                        ->update($data);
    }

    public function autoNumber()
    {
        return $this->db->table($this->table)
                        ->select('id_buku')
                        ->orderBy('id_buku', 'DESC')
                        ->limit(1)
                        ->get();
    }
}
