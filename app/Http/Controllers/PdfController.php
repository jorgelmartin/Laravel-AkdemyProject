<!-- <?php

// namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

// class PdfController extends Controller
{
    // public function generarPdf()
    {
        $data = [
            'titulo' => 'Ejemplo de PDF con Laravel Dompdf',
            'contenido' => 'Este es un contenido de ejemplo que se mostrará en el PDF.',
        ];

        // $pdf = PDF::loadView('pdf.python', $data);

        // return $pdf->download('Curso_de_Python.pdf');
    }
};