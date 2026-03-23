<?php
namespace App\Controllers;
use Picqer\Barcode\BarcodeGeneratorPNG;

class CodigoBarras extends BaseController
{
    public function test($cadena = null){
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($cadena, $generator::TYPE_CODE_128, 3, 150);
        /*$data['barcode'] = base64_encode($barcode);
        return view('codigo_barras', $data);*/
        echo base64_encode($barcode);
    }
}
