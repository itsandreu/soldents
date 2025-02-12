@php
use App\Models\Paciente;
use App\Models\Persona;
use Illuminate\Support\Str;
use Carbon\Carbon;

$hoy = Carbon::today()->toDateString();
$trabajosHoy = $trabajos->filter(fn($trabajo) => $trabajo->salida == $hoy);
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
    <div class="p-4 bg-yellow-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-md">
            <h3 class="text-xl font-bold flex items-center">
                ⚠️ Aviso: Trabajos a Entregar Hoy
            </h3>

            @if ($trabajosHoy->isNotEmpty())
                <ul class="mt-4">
                    @foreach ($trabajosHoy as $trabajo)
                        @php
                            $paciente = Paciente::find($trabajo->paciente_id);
                            $persona = $paciente ? Persona::find($paciente->persona_id) : null;
                        @endphp
                        <li>
                            <a href="{{ route('filament.admin.resources.trabajos.edit', $trabajo->id) }}" 
                                class="flex justify-between items-center p-3 bg-white border border-red-300 rounded-md hover:bg-red-50 transition">
                                <span class="font-semibold text-gray-700">{{ Str::limit($trabajo->descripcion, 100, '...') }}</span>
                                <span class="text-sm font-bold text-red-600">{{ $trabajo->salida }}</span>
                                <span class="text-sm text-gray-500">{{ $trabajo->color_boca }}</span>
                                <span class="text-sm text-gray-500">{{ $persona ? $persona->nombre : 'Sin paciente' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4 text-gray-700">✅ No hay trabajos programados para hoy.</p>
            @endif
        </div>
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
