<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Product_Brand extends Model
{
    protected $table = 'product_brand';
    protected $primaryKey = 'brand_id';
    // protected $useAutoIncrement = true;
    protected $allowedFields = ['brand_name'];

    public function GetDataProductBrand()
    {
        $this->select();
        return $this->get()->getResultArray();
    }
}
