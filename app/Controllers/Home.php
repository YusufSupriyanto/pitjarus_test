<?php

namespace App\Controllers;

use App\Models\M_Product;
use App\Models\M_Product_Brand;
use App\Models\M_Report_Product;
use App\Models\M_Store;
use App\Models\M_Store_Area;

class Home extends BaseController
{

    private M_Store_Area $store_area;

    private M_Report_Product $report_product;

    private M_Store $store;

    private M_Product $product;

    private M_Product_Brand $brand;

    public function __construct()
    {
        $this->store_area = new M_Store_Area();
        $this->report_product = new M_Report_Product();
        $this->store = new M_Store();
        $this->product = new M_Product();
        $this->brand = new M_Product_Brand();
    }
    public function index()
    {

        $data = [
            'store_area' => $this->store_area->GetDataArea(),
            'report_product' => $this->report_product->GetDataReportProductDistinct()
        ];
        return view('test', $data);
    }

    public function GetDateTo()
    {
        $date_from = $this->request->getPost('date_from');

        if ($date_from != '') {
            $date_to = $this->report_product->GetDateToReport($date_from);
        } else {
            $date_to = [];
        }
        $data = [];

        $html = $this->HtmlDateTo($date_to);

        array_push($data, $html);
        echo json_encode($data);
    }

    public function HtmlDateTo($date_to)
    {
        $body = '';
        $head = '<option value="">Select Date To</option>';
        if (!empty($date_to)) {

            foreach ($date_to as $to) {
                $body .= '
            <option value="' . $to['tanggal'] . '">' . $to['tanggal'] . '</option>';
            }

            return $head . $body;
        } else {
            return $head;
        }
    }

    public function GetChartTable()
    {
        $area_id = $this->request->getPost('area_id');
        $date_from = $this->request->getPost('date_from');
        $date_to = $this->request->getPost('date_to');

        $data = [];

        $results = $this->GetArea($area_id, $date_from, $date_to);
        $getValue = $this->GetValue($results);
        $table = $this->TableHtml($area_id, $date_from, $date_to);

        array_push($data, $getValue);
        array_push($data, $table);



        echo json_encode($data);
    }

    public function GetArea($area_id, $date_from, $date_to)
    {
        $result = [];
        foreach ($area_id as $area) {
            $store =  $this->store->GetValuechart($area, $date_from, $date_to);
            array_push($result, $store);
        }
        return $result;
    }

    public function GetValue($values)
    {
        $result = [];
        foreach ($values as $value) {
            $data = [
                'name' => $value[0]['area_name'],
                'y' => $value[0]['value'] / $value[0]['total']  * 100
            ];
            array_push($result, $data);
        }
        return $result;
    }


    public function TableHtml($area_id, $date_from, $date_to)
    {
        $brand = $this->brand->GetDataProductBrand();

        $B = '';
        $D = '';
        $F = '';
        $A = '<div  style="width:50%">
        <table class="table" id="brand">
                    <thead>
                        <tr>
                        <th>BRAND</th>';
        foreach ($area_id as $area) {
            $location = $this->store_area->GetDataArea($area);
            $B .= '<th>' . $location[0]['area_name'] . '</th>';
        }
        $C = '</tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>' . $brand[0]['brand_name'] . '</td>';
        foreach ($area_id as $area) {
            $tr1 = $this->product->GetProduct($brand[0]['brand_id'], $area, $date_from, $date_to);
            $value1 = $tr1[0]['value'] / $tr1[0]['total'] * 100;
            $D .= '<td>' . number_format($value1, 2, ',', ' ') . '%</td>';
        }
        $E = '</tr>
                        <tr>
                         <td>' . $brand[1]['brand_name'] . '</td>';
        foreach ($area_id as $area) {
            $tr2 = $this->product->GetProduct($brand[1]['brand_id'], $area, $date_from, $date_to);
            $value2 = $tr2[0]['value'] / $tr2[0]['total'] * 100;
            $F .= '<td>' . number_format($value2, 2, ',', ' ')  . '%</td>';
        }
        $G = '</tr>
                    </tbody>
                </table>
                
                </div>';

        $html = $A . $B . $C . $D . $E . $F . $G;
        return $html;
    }
}
