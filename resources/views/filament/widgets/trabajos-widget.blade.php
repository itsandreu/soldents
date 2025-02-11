@php
use App\Models\Paciente;
use App\Models\Persona;
@endphp
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-xl font-semibold">Trabajos Recientes</h3>
            
            <!-- Muestra los trabajos -->
            <ul class="mt-4">
                @foreach ($trabajos as $trabajo)
                    <li class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-700 font-medium">{{ $trabajo->descripcion }}</span>
                        <span class="text-sm text-gray-500">{{ $trabajo->created_at->format('d-m-Y') }}</span>  <!-- Muestra la fecha de creación -->
                        <span class="text-sm text-gray-500">{{ $trabajo->color_boca }}</span>
                        @php
                            $paciente = Paciente::where('id',$trabajo->paciente_id)->first();
                            $persona = Persona::where('id',$paciente->persona_id)->first();
                        @endphp
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
