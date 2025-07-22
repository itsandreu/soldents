@php
use App\Models\Paciente;
use App\Models\Persona;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Trabajo;

$hoy = Carbon::today()->toDateString();

$trabajosHoy = Trabajo::whereDate('salida', '=', $hoy)
    ->orderBy('salida', 'asc')
    ->get();

@endphp
<x-filament-widgets::widget>
    <x-filament::section wire:poll.3s class="max-h-[700px]">
        <div class="p-4 rounded-lg shadow-md 
                    max-h-[650px] overflow-y-auto pr-1
                    scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-gray-400 hover:scrollbar-thumb-gray-500"style="background-color: #85cccd;">
            <h3 class="text-base font-semibold mb-2 text-center">Próximos Trabajos</h3>
            @php
                $estadoColors = [
                    'Pendiente' => ['bg' => 'bg-red-100', 'border' => 'border-red-400', 'text' => 'text-black'],
                    'Terminado' => ['bg' => 'bg-sky-100', 'border' => 'border-sky-400', 'text' => 'text-black'],
                    'Diseñado' => ['bg' => 'bg-orange-100', 'border' => 'border-amber-400', 'text' => 'text-black'],
                    'Fresado' => ['bg' => 'bg-violet-100', 'border' => 'border-violet-400', 'text' => 'text-black'],
                    'Sinterizado' => ['bg' => 'bg-green-100', 'border' => 'border-green-400', 'text' => 'text-black'],
                ];
            @endphp
            <ul class="space-y-2 mt-3">
                @forelse (
                    $trabajos->filter(fn($trabajo) => \Carbon\Carbon::parse($trabajo->salida)
                            ->greaterThanOrEqualTo(today()))
                            ->sortBy('salida') as $trabajo
                )
                    @php
                        $paciente = \App\Models\Paciente::find($trabajo->paciente_id);
                        $persona = $paciente ? \App\Models\Persona::find($paciente->persona_id) : null;
                        $estadoNombre = $trabajo->estado?->nombre ?? 'Pendiente';
                        $colores = $estadoColors[$estadoNombre] ?? ['bg' => 'bg-gray-100', 'border' => 'border-gray-300', 'text' => 'text-black'];
                        $esHoy = \Carbon\Carbon::parse($trabajo->salida)->isToday();
                    @endphp
                    <li class="relative">
                        {{-- Marca de agua "HOY" --}}
                        @if ($esHoy)
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                                <span class="text-red-400 text-5xl font-bold opacity-50 select-none">HOY</span>
                            </div>
                        @endif
                
                        <a href="{{ route('filament.admin.registro.resources.trabajos.edit', $trabajo->id) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                           class="relative z-10 block rounded-md px-2 py-1 border shadow-sm transition duration-200 hover:shadow-md hover:brightness-110
                               {{ $esHoy 
                                    ? 'bg-yellow-100 border-yellow-400 text-black animate-pulse ring-2 ring-yellow-300' 
                                    : $colores['bg'].' '.$colores['border'].' '.$colores['text'] }}">
                            <svg class="absolute top-2 right-2 w-5 h-5 text-gray-400 opacity-30 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="font-medium text-sm truncate">
                                {{ $persona?->nombre }} {{ $persona?->apellidos }} – {{ $persona?->clinica?->nombre }}
                            </div>
                            <div class="text-xs text-gray-600 truncate">{{ $trabajo->descripcion }}</div>
                            <div class="text-[10px] text-gray-500 mt-1">{{ \Carbon\Carbon::parse($trabajo->salida)->format('d-m-Y') }}</div>
                        </a>
                    </li>
                @empty
                    <p class="mt-3 text-xs text-gray-500">No hay trabajos recientes.</p>
                @endforelse
            </ul>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>





