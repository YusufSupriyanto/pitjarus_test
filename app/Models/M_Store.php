<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Store extends Model
{
    protected $table = 'store';
    protected $primaryKey = 'store_id';
    // protected $useAutoIncrement = true;
    protected $allowedFields = ['store_name', 'account_id', 'area_id', 'is_active'];

    public function GetValuechart($area_id, $date_from, $date_to)
    {
        $this->select('sum(compliance) as value ,count(compliance) as total,area_name')->where('store.area_id', $area_id)->where('tanggal >=', $date_from)->where('tanggal <=', $date_to);
        $this->join('report_product', 'report_product.store_id=store.store_id');
        $this->join('store_area', 'store_area.area_id=store.area_id');
        return $this->get()->getResultArray();
    }
}