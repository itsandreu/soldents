<x-filament-widgets::widget class="h-full bg-sky-100 dark:bg-rose-100 shadow-xl rounded-xl p-6 flex flex-col justify-center items-center text-center">
    <x-heroicon-o-currency-euro class="w-7 h-7 text-purple mb-2" />
    <div class="text-sm font-medium text-black-100 mb-1">
        Total facturado
    </div>
    <div class="text-3xl font-extrabold text-lime-800">
        {{ number_format(\App\Models\Factura::sum('precio'), 2) }} €
    </div>
    <div class="text-xs text-black-200 mt-1">
        Suma total del importe de todas las facturas
    </div>
</x-filament-widgets::widget>
