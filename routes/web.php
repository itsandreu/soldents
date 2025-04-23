<?php

use App\Models\Trabajo;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/qr/estado/{token}', function ($token) {
    $trabajo = Trabajo::where('qr_token', $token)->firstOrFail();
    if ($trabajo->estado_id >= 1 && $trabajo->estado_id < 5) {
        $trabajo->estado_id += 1;
        $trabajo->save();
    }
    // return response()->view('qr.estado_actualizado', ['trabajo' => $trabajo]);
})->name('qr.estado');

Route::get('/pdf/trabajo/{id}', function ($id) {
    $record = \App\Models\Trabajo::findOrFail($id);

    $entrada = \Carbon\Carbon::parse($record->entrada)->translatedFormat('d/m');
    $salida = \Carbon\Carbon::parse($record->salida)->translatedFormat('d/m');
    $clinicaNombre = $record->paciente->persona->clinica->nombre ?? 'Clínica no asignada';
    $clinicaTelefono = $record->paciente->persona->clinica->telefono ?? 'Teléfono no asignado';
    // $qrUrl = route('qr.estado', ['token' => $record->qr_token]);
    $qrUrl = ' https://faa7-91-242-154-48.ngrok-free.app/qr/estado/' . $record->qr_token;
    $qrImage = base64_encode(QrCode::format('svg')->size(200)->generate($qrUrl));
    $html = view('plantilla_trabajos', compact('record', 'entrada', 'salida', 'clinicaNombre', 'clinicaTelefono','qrUrl','qrImage'))->render();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $filename = 'trabajo_' . preg_replace('/\s+/', '_', $record->paciente->nombre) . '_' . now()->format('Ymd_His') . '.pdf';
    // return response($dompdf->output(), 200)->header('Content-Type', 'application/pdf');
    return response($dompdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
})->name('generar.pdf');