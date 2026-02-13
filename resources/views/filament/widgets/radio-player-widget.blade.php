<x-filament-widgets::widget>
    <x-filament::section class="overflow-hidden">
        {{-- Glavni kontejner sa pollingom na 5 sekundi --}}
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 py-4 px-2" wire:poll.5s>

            <div class="flex items-center gap-6 flex-1 w-full lg:w-auto">
                <div class="relative group">
                    <div class="w-20 h-20 bg-gradient-to-tr from-primary-600 to-primary-400 rounded-full flex items-center justify-center shadow-xl animate-[spin_8s_linear_infinite] ring-4 ring-primary-500/20">
                        <x-heroicon-s-musical-note class="w-10 h-10 text-white" />
                        <div class="absolute w-3 h-3 bg-white dark:bg-gray-900 rounded-full shadow-inner"></div>
                    </div>

                    <div class="absolute -top-1 -right-1 flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-danger-600 border-2 border-white dark:border-gray-800"></span>
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-danger-100 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400 uppercase tracking-widest">
                        Uživo u etru
                    </span>
                    <h2 class="text-2xl font-black tracking-tight text-gray-950 dark:text-white leading-tight">
                        {{ $current?->title ?? 'Tišina na talasima...' }}
                    </h2>
                    <p class="text-lg font-medium text-primary-600 dark:text-primary-400 flex items-center gap-2">
                        <x-heroicon-m-user class="w-4 h-4" />
                        {{ $current?->artist ?? 'StandardClassic' }}
                    </p>
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-8 flex-1 border-s border-gray-100 dark:border-gray-800 ps-8">
                <div class="space-y-2">
                    <p class="text-[10px] font-bold uppercase text-gray-400 tracking-[0.2em]">Slijedi</p>
                    <div class="flex items-center gap-3 group">
                        <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded-lg group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 transition-colors">
                            <x-heroicon-m-forward class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200 line-clamp-1">
                                {{ $next?->title ?? 'Kraj liste' }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $next?->artist }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end w-full lg:w-auto border-t lg:border-t-0 pt-4 lg:pt-0">
                <x-filament::button
                    wire:click="skip"
                    wire:loading.attr="disabled"
                    color="warning"
                    icon="heroicon-m-forward"
                    size="xl"
                    class="rounded-xl shadow-lg hover:shadow-warning-500/20 transition-all active:scale-95 w-full lg:w-auto"
                >
                    <span class="font-black tracking-wide uppercase">Preskoči</span>
                </x-filament::button>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-100 dark:border-gray-800 pt-4">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex-1 w-full text-center sm:text-left">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Slušaj stream uživo</p>
                    <audio id="radio-stream" controls class="w-full h-10 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                        {{-- Zamijeni IP adresu ako je drugačija od localhost --}}
                        <source src="http://localhost:8000/radio.mp3" type="audio/mpeg">
                        Vaš pretraživač ne podržava audio element.
                    </audio>
                </div>
            </div>
            <div class="flex items-center justify-center gap-2 mt-2">
                <span class="w-1.5 h-1.5 bg-success-500 rounded-full animate-pulse"></span>
                <p class="text-[9px] text-gray-400 italic">
                    Stream je aktivan na http://localhost:8000/radio.mp3
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
