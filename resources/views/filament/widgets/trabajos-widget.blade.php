@php
use App\Models\Paciente;
use App\Models\Persona;
use Illuminate\Support\Str;
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-xl font-semibold">Trabajos Recientes</h3>
            
            <!-- Muestra los trabajos -->
            <ul class="mt-4">
                @foreach ($trabajos as $trabajo)
                    @php
                        $paciente = Paciente::where('id', $trabajo->paciente_id)->first();
                        $persona = Persona::where('id', $paciente->persona_id)->first();
                    @endphp
                    <li class="grid grid-cols-4 gap-4 py-2 border-b">
                        <span class="text-gray-700 font-medium truncate">{{ Str::limit($trabajo->descripcion, 100, '...') }}</span>
                        <span class="text-sm text-gray-500">{{ $trabajo->created_at->format('d-m-Y') }}</span>
                        <span class="text-sm text-gray-500">{{ $trabajo->color_boca }}</span>
                        <span class="text-sm text-gray-500">{{ $persona->nombre }}</span>
                    </li>
                @endforeach
            </ul>
        
            <!-- Si no hay trabajos -->
            @if ($trabajos->isEmpty())
                <p class="mt-4 text-gray-500">No hay trabajos recientes.</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>