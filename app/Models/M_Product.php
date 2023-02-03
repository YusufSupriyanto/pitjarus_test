<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';
    // protected $useAutoIncrement = true;
    protected $allowedFields = ['product_name', 'brand_id'];

    public function GetProduct($brand_id, $area_id, $date_from, $date_to)
    {
        $this->select('brand_name,sum(compliance) as value,count(compliance) as total');
        $this->join('product_brand', 'product_brand.brand_id=product.brand_id')->where('product_brand.brand_id', $brand_id);
        $this->join('report_product', 'report_product.product_id=product.product_id')->where('report_product.tanggal >=', $date_from)->where('report_product.tanggal <=', $date_to);
        $this->join('store', 'store.store_id=report_product.store_id');
        $this->join('store_area', 'store_area.area_id=store.area_id')->where('store_area.area_id', $area_id);
        return $this->get()->getResultArray();
    }
}
