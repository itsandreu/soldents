<?php

use App\Models\Trabajo;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Laragear\WebAuthn\Http\Controllers\WebAuthnLoginController;
use Laragear\WebAuthn\Http\Controllers\WebAuthnRegisterController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/phpinfo', fn () => phpinfo());

Route::get('/pdf/trabajo/{id}', function ($id) {
    $record = \App\Models\Trabajo::findOrFail($id);

    $entrada = \Carbon\Carbon::parse($record->entrada)->translatedFormat('d/m');
    $salida = \Carbon\Carbon::parse($record->salida)->translatedFormat('d/m');
    $clinicaNombre = $record->paciente->persona->clinica->nombre ?? 'Clínica no asignada';
    $clinicaTelefono = $record->paciente->persona->clinica->telefono ?? 'Teléfono no asignado';
    // $qrUrl = route('qr.estado', ['token' => $record->qr_token]);
    $qrUrl = ' https://www.ricardoandreu.com/qr/estado/' . $record->qr_token;
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

Route::get('/documento/trabajo/{id}', function ($id) {
    $record = \App\Models\Trabajo::findOrFail($id);

    $entrada = \Carbon\Carbon::parse($record->entrada)->translatedFormat('d/m');
    $salida = \Carbon\Carbon::parse($record->salida)->translatedFormat('d/m');
    $clinicaNombre = $record->paciente->persona->clinica->nombre ?? 'Clínica no asignada';
    $clinicaTelefono = $record->paciente->persona->clinica->telefono ?? 'Teléfono no asignado';
    $html = view('plantilla_documento_trabajos', compact('record', 'entrada', 'salida', 'clinicaNombre', 'clinicaTelefono'))->render();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $filename = 'trabajo_documento_' . preg_replace('/\s+/', '_', $record->paciente->nombre) . '_' . now()->format('Ymd_His') . '.pdf';
    // return response($dompdf->output(), 200)->header('Content-Type', 'application/pdf');
    return response($dompdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
})->name('generar.documento');


// Route::get('/qr/estado/{token}', function ($token) {
//     // $trabajo = Trabajo::where('qr_token', $token)->firstOrFail();
//     // if ($trabajo->estado_id >= 1 && $trabajo->estado_id < 5) {
//     //     $trabajo->estado_id += 1;
//     //     $trabajo->save();
//     // }
//     $trabajo = Trabajo::where('qr_token', $token)->firstOrFail();

//     // Comprobar si aún puede avanzar
//     if ($trabajo->estado_id >= 1 && $trabajo->estado_id < 5) {
//         // Solo cambiar si el estado no ha cambiado ya en este escaneo
//         $ultimo_estado = session('ultimo_estado_'.$trabajo->id);

//         if ($ultimo_estado != $trabajo->estado_id) {
//             $trabajo->estado_id += 1;
//             $trabajo->save();
//             session(['ultimo_estado_'.$trabajo->id => $trabajo->estado_id]);
//         }
//     }
//     // return response()->view('qr.estado_actualizado', ['trabajo' => $trabajo]);
// })->name('qr.estado');
