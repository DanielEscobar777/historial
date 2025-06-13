<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Servicios;
use App\Models\Evolucion;
use App\Models\Diagnostico_soap;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
  public function generatePdf($id_historia)
  {
    $h_antecedentes = Historial::traerantecedentes($id_historia);
    $h_examen_general = Historial::traerExamenGeneral($id_historia);
    $h_examen_segmentado = Historial::traerexamensegmentado($id_historia);
    $h_examen_segmentado_mi = Historial::traerexamensegmentado_mi($id_historia);
    $h_sistema_nervioso = Historial::traerexamensistemanervioso($id_historia);
    $h_examenes = Historial::traerdiagnosticos($id_historia);
    $imagePath = public_path('images/silueta.jpg');
    $id_servicio = $h_antecedentes->id_servicio;
    $permisos = Servicios::permisos_n1($id_servicio);
    $data = [
      'imagePath' => $imagePath,
      'h_antecedentes' => $h_antecedentes,
      'h_examen_general' => $h_examen_general,
      'h_examen_segmentado' => $h_examen_segmentado,
      'h_examen_segmentado_mi' => $h_examen_segmentado_mi,
      'h_sistema_nervioso' => $h_sistema_nervioso,
      'h_examenes' => $h_examenes,
      'permisos' => $permisos
    ];

    return Pdf::loadView('pdf.documento', $data)->stream();
  }

  public function generateSOAP($id_historia)
  {
    $evoluciones = Evolucion::traerEvolucion($id_historia);
    $diagnostico = Diagnostico_soap::traerDiagnostico($id_historia);
    $data = [
      'evoluciones' => $evoluciones,
      'diagnosticos' => $diagnostico,
    ];
    return Pdf::loadView('pdf.soap', $data)->stream();
  }


   public function generateSOAP_internos($id_evolucion)
  {
    $evolucion = Evolucion::where('id_evolucion', $id_evolucion)->first();
    $diagnostico = Diagnostico_soap::where('id_evolucion', $id_evolucion)->get();

    $data = [
      'evolucion' => $evolucion,
      'diagnosticos' => $diagnostico,
    ];
    
    return Pdf::loadView('pdf.soap_internos', $data)->stream();
  }
}
