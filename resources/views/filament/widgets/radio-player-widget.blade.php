<x-filament-widgets::widget>
    <x-filament::section class="overflow-hidden">
        @php
            // Provjera da li je Liquidsoap aktivan (koristimo istu logiku kao u ServiceStatusWidget-u)
            // Timeout je postavljen na veoma nisko (0.2s) da ne bi kočio renderovanje stranice
            $liquidsoapSocket = @fsockopen(config('radio.icecast_host'), config('radio.icecast_telnet_port'), $errno, $errstr, 0.2);
            $isLive = is_resource($liquidsoapSocket);
            if ($isLive) {
                fclose($liquidsoapSocket);
            }
        @endphp

        {{-- Glavni kontejner sa pollingom na 5 sekundi --}}
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 py-4 px-2" wire:poll.5s>

            <div class="flex items-center gap-6 flex-1 w-full lg:w-auto">
                <div class="relative group">
                    {{-- Disk se vrti samo ako je sistem uživo --}}
                    <div @class([
                        'w-20 h-20 rounded-full flex items-center justify-center shadow-xl ring-4 ring-primary-500/20 transition-all duration-700',
                        'bg-gradient-to-tr from-primary-600 to-primary-400 animate-[spin_8s_linear_infinite]' => $isLive,
                        'bg-gray-300 dark:bg-gray-700 opacity-50' => !$isLive,
                    ])>
                        @if($current?->image_path)
                            <img src="{{ Storage::disk('radio-covers')->url($current->image_path) }}"
                                 alt="{{ $current->title }}"
                                 class="w-full h-full object-cover opacity-90 rounded-full">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-gray-800 to-gray-950">
                                <x-heroicon-s-musical-note class="w-10 h-10 text-gray-600" />
                            </div>
                        @endif
                        <div class="absolute w-3 h-3 bg-white dark:bg-gray-900 rounded-full shadow-inner"></div>
                    </div>

                    @if($isLive)
                        <div class="absolute -top-1 -right-1 flex h-5 w-5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-5 w-5 bg-danger-600 border-2 border-white dark:border-gray-800"></span>
                        </div>
                    @endif
                </div>

                <div class="space-y-1">
                    @if($isLive)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-danger-100 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400 uppercase tracking-widest">
                            Uživo u etru
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 uppercase tracking-widest">
                            Sistem pauziran
                        </span>
                    @endif

                    <h2 @class([
                        'text-2xl font-black tracking-tight leading-tight transition-colors',
                        'text-gray-950 dark:text-white' => $isLive,
                        'text-gray-400 dark:text-gray-600' => !$isLive,
                    ])>
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
                    :disabled="!$isLive"
                    color="warning"
                    icon="heroicon-m-forward"
                    size="xl"
                    class="rounded-xl shadow-lg hover:shadow-warning-500/20 transition-all active:scale-95 w-full lg:w-auto uppercase font-black tracking-wide"
                >
                    Preskoči
                </x-filament::button>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-100 dark:border-gray-800 pt-4">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex-1 w-full text-center sm:text-left">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Slušaj stream uživo (Preview)</p>
                    <audio id="radio-stream" controls class="w-full h-10 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                        <source src="{{ $source }}" type="audio/mpeg">
                        Vaš pretraživač ne podržava audio element.
                    </audio>
                </div>
            </div>
            <div class="flex items-center justify-center gap-2 mt-2">
                @if($isLive)
                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-gray-400 italic">
                        Radio stanica emituje program uživo
                    </p>
                @else
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                    <p class="text-[9px] text-gray-400 italic">
                        Emitovanje je trenutno prekinuto (Provjerite status servisa)
                    </p>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<script>
    document.addEventListener('livewire:load', function () {
        const audio = document.getElementById('radio-stream');
        // Sprečavamo Livewire da resetuje plejer ako već svira
        audio.addEventListener('play', () => {
            audio.setAttribute('data-playing', 'true');
        });
    });
</script>
