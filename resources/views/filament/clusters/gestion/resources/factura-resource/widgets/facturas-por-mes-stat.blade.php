<x-filament-widgets::widget>
    <x-filament::card>
        <div class="text-center font-bold text-lg mb-4">
            Facturas por mes <span class="bg-lime-200 text-lime-800 px-2 py-1 rounded text-sm">(Bubble Chart)</span>
        </div>

        <div class="grid grid-cols-12 gap-1 items-end justify-items-center w-full h-80">
            @foreach ($data as $index => $valor)
                @php
                    $isZero = $valor === 0;
                @endphp

                <div class="flex flex-col items-center justify-end">
                    {{-- Burbujas uniformes, opacas si son 0 --}}
                    <div
                        class="w-[65px] h-[65px] rounded-full shadow-md flex items-center justify-center text-white text-base font-semibold transition-transform duration-200 hover:scale-105 cursor-pointer"
                        style="background-color: {{ $isZero ? '#d1d5db' : '#10B981' }};"
                        x-data="{ show: false }"
                        @mouseenter="show = true"
                        @mouseleave="show = false"
                    >
                        {{ $valor }}

                        {{-- Tooltip elegante --}}
                        @if (!$isZero)
                            <div
                                x-show="show"
                                x-transition
                                class="absolute bottom-full mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded shadow z-50 whitespace-nowrap"
                            >
                                {{ $labels[$index] }}: {{ $valor }} factura{{ $valor == 1 ? '' : 's' }}
                            </div>
                        @endif
                    </div>

                    {{-- Etiqueta del mes --}}
                    <div class="text-sm mt-2 text-gray-800 font-medium text-center w-16 truncate">
                        {{ substr($labels[$index], 0, 3) }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::card>
</x-filament-widgets::widget>