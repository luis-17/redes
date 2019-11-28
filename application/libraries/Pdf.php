<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    //require_once APPPATH."/fpdf/fpdf.php";
    require_once(dirname(__FILE__) . '/fpdf/fpdf.php');
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image(base_url().'assets/images/logo.png',8,8,60);
            $this->Ln('5');            
            $this->SetFont('Arial','B',13);            
            $this->Cell(30); 
            $this->Line(10, 30 , 200, 30);         
       }

       // El pie del pdf
       public function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,utf8_decode('Página ').''.$this->PageNo().'/{nb}',0,0,'C');
      }
    }
?>