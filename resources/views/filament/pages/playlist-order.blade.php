<x-filament-panels::page>
    <div wire:poll.10s class="space-y-6">
        @php
            $playlistItems = collect($playlist_rows ?? []);

            $formatDuration = fn (?int $seconds): string => $seconds === null
                ? 'Nepoznato'
                : gmdate($seconds >= 3600 ? 'H:i:s' : 'i:s', $seconds);

            $typePresentation = fn (?string $type): array => match (strtolower((string) $type)) {
                'song' => [
                    'label' => 'Pjesma',
                    'icon' => 'heroicon-o-musical-note',
                    'classes' => 'bg-primary-50 text-primary-700 ring-primary-200 dark:bg-primary-950/30 dark:text-primary-300 dark:ring-primary-800',
                ],
                'show' => [
                    'label' => 'Emisija',
                    'icon' => 'heroicon-o-microphone',
                    'classes' => 'bg-success-50 text-success-700 ring-success-200 dark:bg-success-950/30 dark:text-success-300 dark:ring-success-800',
                ],
                'podcast' => [
                    'label' => 'Podkast',
                    'icon' => 'heroicon-o-megaphone',
                    'classes' => 'bg-warning-50 text-warning-700 ring-warning-200 dark:bg-warning-950/30 dark:text-warning-300 dark:ring-warning-800',
                ],
                'jingle' => [
                    'label' => 'Džingl',
                    'icon' => 'heroicon-o-bolt',
                    'classes' => 'bg-info-50 text-info-700 ring-info-200 dark:bg-info-950/30 dark:text-info-300 dark:ring-info-800',
                ],
                default => [
                    'label' => 'Nepoznat tip',
                    'icon' => 'heroicon-o-question-mark-circle',
                    'classes' => 'bg-gray-100 text-gray-700 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700',
                ],
            };

            $statusPresentation = fn (string $status): array => match ($status) {
                'interrupted' => [
                    'label' => 'Prekinuto terminom',
                    'classes' => 'bg-warning-50 text-warning-700 dark:bg-warning-950/30 dark:text-warning-300',
                ],
                'current' => [
                    'label' => 'U etru',
                    'classes' => 'bg-success-50 text-success-700 dark:bg-success-950/30 dark:text-success-300',
                ],
                'unforecasted' => [
                    'label' => 'Nema procjene',
                    'classes' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                ],
                default => [
                    'label' => 'Predstojeće',
                    'classes' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                ],
            };
        @endphp

        <div class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-1 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-950/30 dark:text-primary-300">
                        <x-filament::icon icon="heroicon-o-arrows-up-down" class="h-5 w-5" />
                    </span>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Uređivanje programa</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Aktivna plejlista: {{ $playlist_name ?: 'Nije odabrana' }} · {{ $playlistItems->count() }} stavki
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 sm:justify-end dark:text-gray-400">
                <span>Očekivani termini se osvježavaju svakih 10 sekundi.</span>
                <a href="{{ \App\Filament\Pages\Program::getUrl() }}" class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-2 font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    <x-filament::icon icon="heroicon-o-clock" class="h-4 w-4" />
                    Predstojeći program
                </a>
            </div>
        </div>

        @if (count($warnings))
            <div class="rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm text-warning-800 dark:border-warning-800 dark:bg-warning-950/30 dark:text-warning-200">
                @foreach ($warnings as $warning)
                    <p>{{ $warning }}</p>
                @endforeach
            </div>
        @endif

        <div class="flex items-start gap-3 rounded-xl border border-info-200 bg-info-50 px-4 py-3 text-sm text-info-800 dark:border-info-800 dark:bg-info-950/30 dark:text-info-200">
            <x-filament::icon icon="heroicon-o-lock-closed" class="mt-0.5 h-5 w-5 shrink-0" />
            <p>Trenutna i naredne dvije stavke su zaključane jer su već učitane u audio bafer. Promjena njihovog redoslijeda ne bi uticala na već pripremljenu reprodukciju.</p>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-white/10">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Redoslijed stavki</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Prevuci stavku pomoću ručice. Linija pokazuje gdje će stavka biti spuštena.</p>
                </div>
                <div class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                    <x-filament::icon icon="heroicon-o-play-circle" class="h-4 w-4" />
                    Regularne stavke plejliste
                </div>
            </div>

            @if ($playlistItems->isEmpty())
                <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">Nema stavki u aktivnoj plejlisti.</div>
            @else
                <div x-data="{ dragged: null, dropTarget: null, dropBefore: false }" class="divide-y divide-gray-200 dark:divide-white/10">
                    @foreach ($playlistItems as $playlistItem)
                        @php
                            $playlistType = $typePresentation($playlistItem['type']);
                            $playlistStatus = $statusPresentation($playlistItem['status']);
                            $playlistId = (int) $playlistItem['playlist_media_id'];
                            $isReorderable = (bool) ($playlistItem['reorderable'] ?? false);
                        @endphp

                        <article
                            wire:key="playlist-item-{{ $playlistId }}"
                            class="relative grid gap-4 px-4 py-4 transition-colors sm:grid-cols-[3rem_minmax(0,1fr)_13rem] sm:items-center sm:px-5 {{ $isReorderable ? 'hover:bg-gray-50 dark:hover:bg-white/[0.03]' : 'bg-gray-50/70 dark:bg-white/[0.03]' }}"
                            @if ($isReorderable)
                                draggable="true"
                                data-playlist-media-id="{{ $playlistId }}"
                                x-on:dragstart="dragged = $event.currentTarget.dataset.playlistMediaId; $event.dataTransfer.effectAllowed = 'move'"
                                x-on:dragover.prevent="if (dragged !== '{{ $playlistId }}') { dropTarget = '{{ $playlistId }}'; dropBefore = $event.offsetY < ($event.currentTarget.offsetHeight / 2) }"
                                x-on:dragleave="if (dropTarget === '{{ $playlistId }}') dropTarget = null"
                                x-on:drop.prevent="if (dragged && dragged !== '{{ $playlistId }}') { $wire.movePlaylistItem(dragged, '{{ $playlistId }}', dropBefore); dragged = null; dropTarget = null }"
                                x-on:dragend="dragged = null; dropTarget = null"
                                x-bind:class="dropTarget === '{{ $playlistId }}' ? 'bg-primary-50/70 dark:bg-primary-950/20' : ''"
                            @endif
                        >
                            @if ($isReorderable)
                                <div x-cloak x-show="dropTarget === '{{ $playlistId }}' && dropBefore" class="pointer-events-none absolute inset-x-4 top-0 z-10 h-0.5 bg-primary-500 sm:inset-x-5"></div>
                                <div x-cloak x-show="dropTarget === '{{ $playlistId }}' && ! dropBefore" class="pointer-events-none absolute inset-x-4 bottom-0 z-10 h-0.5 bg-primary-500 sm:inset-x-5"></div>
                            @endif

                            <div class="flex items-center gap-3 sm:justify-center">
                                @if ($isReorderable)
                                    <span class="cursor-grab text-gray-400 active:cursor-grabbing dark:text-gray-500" title="Prevuci za promjenu redoslijeda" aria-label="Prevuci za promjenu redoslijeda">
                                        <x-filament::icon icon="heroicon-o-bars-3" class="h-5 w-5" />
                                    </span>
                                @else
                                    <span class="text-info-600 dark:text-info-300" title="Stavka je učitana u bafer" aria-label="Stavka je učitana u bafer">
                                        <x-filament::icon icon="heroicon-o-lock-closed" class="h-5 w-5" />
                                    </span>
                                @endif
                                <span class="text-sm font-semibold tabular-nums text-gray-500 dark:text-gray-400">{{ $playlistItem['position'] }}</span>
                            </div>

                            <div class="min-w-0">
                                <div class="mb-1 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700" title="Regularna stavka sa plejliste" aria-label="Regularna stavka sa plejliste">
                                        <x-filament::icon icon="heroicon-o-play-circle" class="h-3.5 w-3.5" />
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $playlistType['classes'] }}">
                                        <x-filament::icon :icon="$playlistType['icon']" class="h-3.5 w-3.5" />
                                        {{ $playlistType['label'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $playlistStatus['classes'] }}">
                                        {{ $playlistStatus['label'] }}
                                    </span>
                                    @if (! $isReorderable)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-info-100 px-2.5 py-1 text-xs font-medium text-info-800 dark:bg-info-900/40 dark:text-info-200">
                                            <x-filament::icon icon="heroicon-o-lock-closed" class="h-3.5 w-3.5" />
                                            Baferovano
                                        </span>
                                    @endif
                                </div>
                                <div class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $playlistItem['title'] }}</div>
                                <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $playlistItem['artist'] ?: 'StandardClassic' }}</div>
                                @if ($playlistItem['interrupted'])
                                    <div class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-warning-700 dark:text-warning-300">
                                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-3.5 w-3.5" />
                                        Završava se zbog zakazane emisije
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-3 text-xs sm:border-t-0 sm:pt-0 sm:text-right dark:border-white/5">
                                <div class="flex items-center gap-2 font-semibold tabular-nums text-gray-900 dark:text-white" title="Očekivani početak i kraj">
                                    <x-filament::icon icon="heroicon-o-play" class="h-3.5 w-3.5 text-gray-400" />
                                    <span>{{ $playlistItem['starts_at']?->format('H:i:s') ?: '—' }}</span>
                                    <span class="font-normal text-gray-400">–</span>
                                    <span>{{ $playlistItem['ends_at']?->format('H:i:s') ?: '—' }}</span>
                                </div>
                                <div class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400" title="Trajanje">
                                    <x-filament::icon icon="heroicon-o-clock" class="h-3.5 w-3.5" />
                                    {{ $formatDuration($playlistItem['duration']) }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
