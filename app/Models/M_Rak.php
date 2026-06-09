<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Rak extends Model
{
    protected $table = 'tbl_rak';

    public function getDataRak($where = false)
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

    public function saveDataRak($data)
    {
        return $this->db->table($this->table)
                        ->insert($data);
    }

    public function updateDataRak($data, $where)
    {
        return $this->db->table($this->table)
                        ->where($where)
                        ->update($data);
    }

    public function autoNumber()
    {
        return $this->db->table($this->table)
                        ->select('id_rak')
                        ->orderBy('id_rak', 'DESC')
                        ->limit(1)
                        ->get();
    }
}
