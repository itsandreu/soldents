@php
use App\Models\Paciente;
use App\Models\Persona;
use Illuminate\Support\Str;
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-xl font-semibold">Proximos Trabajos</h3>
            
            <!-- Muestra los trabajos ordenados por fecha de salida -->
            <ul class="mt-4">
                @foreach ($trabajos->sortBy('salida') as $trabajo)
                    @php
                        $paciente = Paciente::find($trabajo->paciente_id);
                        $persona = $paciente ? Persona::find($paciente->persona_id) : null;
                    @endphp
                    <li>
                        <a href="{{ route('filament.admin.resources.trabajos.edit', $trabajo->id) }}" 
                            class="grid grid-cols-4 gap-4 py-2 border-b hover:bg-gray-100 transition rounded-md px-2">
                            <span class="text-gray-700 font-small truncate ">{{ Str::limit($trabajo->descripcion, 120, '...') }}</span>
                            <span class="text-sm text-gray-500">{{ $trabajo->salida }}</span>
                            <span class="text-sm text-gray-500">{{ $trabajo->color_boca }}</span>
                            <span class="text-sm text-gray-500">{{ $persona ? $persona->nombre : 'Sin paciente' }}</span>
                        </a>
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
