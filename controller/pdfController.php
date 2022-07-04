<?php
require_once  'dompdf/autoload.inc.php';
use dompdf\dompdf;

class pdfController
{
    private $pdf;

    public function _construct(){
        echo "CONSTRUCTOR";
    }

    public function crearPDF(){
        $html="<h2>Harcodeado</h2>";
        $nombrePDF= "NombreHarcodeado";
        $pdf = new Dompdf();//Inicializa
        $pdf->setPaper('A4','landscape');//Se ajusta el papel
        $pdf->loadHtml($html);//lo carga en la hoja el html
        $pdf->render();//renderiza de html a pdf
        $pdf->stream($nombrePDF, ['Attachment'=>0]);//genera el pdf en el navegador /false misma pag / true descarga
        //El ['Attachment'=>0] es para q lo genere en otra pestaña
    }
}