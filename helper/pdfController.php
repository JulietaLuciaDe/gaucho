<?php
require_once  'helper/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
class pdfController
{
    private $pdf;

    public function _construct(){
        $dompdf = new Dompdf();//Inicializa
        $dompdf->setPaper('A4','landscape');//Se ajusta el papel
        $this->pdf=$dompdf;
    }

    public function crearPdf($nombrePDF,$html){
        $this->pdf->loadHtml($html);//lo carga en la hoja el html
        $this->pdf->render();//renderiza de html a pdf
        $this->pdf->stream($nombrePDF, ['Attachment'=>0]);//genera el pdf en el navegador
        //El ['Attachment'=>0] es para q lo genere en otra pestaña
    }
    //podria haber por ej un <a> en el html q haga un href"helper/pdfControler.php?nombrePDF=Ejemplo&&html=Ejemplo
    //O tmb podriamos hacerlo mas directo, en vez de pasar un $html como atributo, pasariamos un html completo ya creado
    //Lo deje en Helper por si es mejor usarlo en otro controlador y fue jajaja

}