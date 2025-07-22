<x-filament-panels::page>
    <div class="flex flex-col items-center justify-start min-h-screen p-6 pt-10 space-y-8">
        <div class="w-full max-w-full bg-white p-6 shadow-md">
            <div class="flex justify-between items-start space-x-2">

                <div class="flex flex-col items-center flex-1 min-w-0">
                    <p class="text-sm text-gray-500 truncate">Trabajo</p>
                    <p class="text-lg font-semibold text-gray-800 truncate">T-{{ $this->trabajo->id }}</p>
                </div>

                <div class="flex flex-col items-center flex-1 min-w-0">
                    <p class="text-sm text-gray-500 truncate">Paciente</p>
                    <p class="text-lg font-semibold text-red-500 text-center truncate">
                        {{ $this->trabajo->paciente->persona->identificador }}
                    </p>
                    <p class="text-lg font-semibold text-gray-800 text-center truncate">
                        {{ $this->trabajo->paciente->persona->nombre }}
                    </p>
                    <p class="text-lg font-semibold text-gray-500 text-center truncate">
                        {{ $this->trabajo->paciente->persona->apellidos }}
                    </p>
                </div>

                <div class="flex flex-col items-center flex-1 min-w-0">
                    <p class="text-sm text-gray-500 truncate">Clínica</p>
                    <p class="text-lg font-semibold text-gray-800 text-center truncate">
                        {{ $this->trabajo->paciente->persona->clinica->nombre }}
                    </p>
                </div>
            </div>
        </div>
        <br>
        <br>

        @if (session('success'))
            <div class="text-green-600 font-semibold animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 w-full max-w-md">
            @foreach ($this->estados as $estado)
                    <button wire:click="cambiarEstado({{ $estado->id }})" wire:loading.attr="disabled"
                        wire:target="cambiarEstado({{ $estado->id }})" class="relative py-4 rounded-full font-bold text-lg text-center transition-all duration-300
                                                                border-2 focus:outline-none overflow-hidden
                                                                {{ $this->trabajo->estado_id == $estado->id
                ? 'bg-green-500 text-white border-green-500 shadow-md scale-105'
                : 'bg-white text-gray-800 border-gray-300 hover:bg-gray-100 hover:scale-105' }}">

                        <div class="flex items-center justify-center space-x-2">
                            <span>{{ $estado->nombre }}</span>

                            <!-- Loader visible solo en botón pulsado -->
                            <svg wire:loading wire:target="cambiarEstado({{ $estado->id }})"
                                class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                        </div>
                    </button>
            @endforeach
        </div>

    </div>
</x-filament-panels::page>