<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\Persona;

class PersonaObserver
{
    /**
     * Handle the Persona "created" event.
     */
    public function created(Persona $persona): void
    {
        if ($persona->tipo ==='paciente') {
            Paciente::create(['persona_id' => $persona->id]);
        }elseif ($persona->tipo === 'doctor') {
            Doctor::create(['persona_id' => $persona->id]);
        }
    }

    /**
     * Handle the Persona "updated" event.
     */
    public function updated(Persona $persona): void
    {
        if ($persona->wasChanged('tipo')) { // Si el tipo cambió
            // Eliminar el rol anterior
            Paciente::where('persona_id', $persona->id)->delete();
            Doctor::where('persona_id', $persona->id)->delete();

            // Crear el nuevo tipo
            if ($persona->tipo === 'paciente') {
                Paciente::create(['persona_id' => $persona->id]);
            } elseif ($persona->tipo === 'doctor') {
                Doctor::create(['persona_id' => $persona->id,'especialidad' => $persona->especialidad]);
            }
        }
    }

    /**
     * Handle the Persona "deleted" event.
     */
    public function deleted(Persona $persona): void
    {
        Paciente::where('persona_id', $persona->id)->delete();
        Doctor::where('persona_id', $persona->id)->delete();
    }

    /**
     * Handle the Persona "restored" event.
     */
    public function restored(Persona $persona): void
    {
        //
    }

    /**
     * Handle the Persona "force deleted" event.
     */
    public function forceDeleted(Persona $persona): void
    {
        //
    }
}
