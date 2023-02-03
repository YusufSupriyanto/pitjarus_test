<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Store_Area extends Model
{
    protected $table = 'store_area';
    protected $primaryKey = 'area_id';
    // protected $useAutoIncrement = true;
    protected $allowedFields = ['area_name'];

    public function GetDataArea($area_id = false)
    {
        if ($area_id == false) {
            $this->select();
            return $this->get()->getResultArray();
        } else {
            $this->select()->where('area_id', $area_id);
            return $this->get()->getResultArray();
        }
    }
}