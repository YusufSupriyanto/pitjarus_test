<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Report_Product extends Model
{
    protected $table = 'report_product';
    protected $primaryKey = 'report_id';
    // protected $useAutoIncrement = true;
    protected $allowedFields = ['store_id', 'product_id', 'compliance', 'tanggal'];

    public function GetDataReportProductDistinct()
    {
        $this->select('tanggal')->Distinct();
        return $this->get()->getResultArray();
    }

    public function GetDateToReport($date_from)
    {
        $this->select('tanggal')->where('tanggal >=', $date_from)->Distinct();
        return $this->get()->getResultArray();
    }
}