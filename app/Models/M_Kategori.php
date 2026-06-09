<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Kategori extends Model
{
    protected $table = 'tbl_kategori';

    public function getDataKategori($where = false)
    {
        if ($where === false) {
            return $this->db->table($this->table)
                            ->get();
        } else {
            return $this->db->table($this->table)
                            ->where($where)
                            ->get();
        }
    }

    public function saveDataKategori($data)
    {
        return $this->db->table($this->table)
                        ->insert($data);
    }

    public function updateDataKategori($data, $where)
    {
        return $this->db->table($this->table)
                        ->where($where)
                        ->update($data);
    }

    public function autoNumber()
    {
        return $this->db->table($this->table)
                        ->select('id_kategori')
                        ->orderBy('id_kategori', 'DESC')
                        ->limit(1)
                        ->get();
    }
}
