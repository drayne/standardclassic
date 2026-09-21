<x-filament-panels::page>
    <div wire:poll.10s class="space-y-6">
        @php
            $current = collect($rows)->firstWhere('kind', 'current');
            $upcoming = collect($rows)->where('kind', '!=', 'current');

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

            $sourcePresentation = fn (string $kind): array => $kind === 'scheduled'
                ? [
                    'label' => 'Zakazana emisija',
                    'icon' => 'heroicon-o-calendar-days',
                    'classes' => 'bg-info-50 text-info-700 ring-info-200 dark:bg-info-950/30 dark:text-info-300 dark:ring-info-800',
                ]
                : [
                    'label' => 'Regularna stavka',
                    'icon' => 'heroicon-o-queue-list',
                    'classes' => 'bg-gray-100 text-gray-700 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700',
                ];

            $statusPresentation = fn (string $status): array => match ($status) {
                'scheduled' => [
                    'label' => 'Zakazano',
                    'classes' => 'bg-info-50 text-info-700 dark:bg-info-950/30 dark:text-info-300',
                ],
                'interrupted' => [
                    'label' => 'Prekinuto terminom',
                    'classes' => 'bg-warning-50 text-warning-700 dark:bg-warning-950/30 dark:text-warning-300',
                ],
                'current' => [
                    'label' => 'U etru',
                    'classes' => 'bg-success-50 text-success-700 dark:bg-success-950/30 dark:text-success-300',
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
                        <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5" />
                    </span>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Raspored emitovanja</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Aktivna plejlista: {{ $playlist_name ?: 'Nije odabrana' }} · pregled do {{ $horizon->format('d.m.Y H:i') }}
                </p>
            </div>
            <div class="text-left text-xs text-gray-500 sm:text-right dark:text-gray-400">
                <span class="font-medium text-gray-700 dark:text-gray-300">Crossfade: {{ $crossfade_seconds }} s</span><br>
                Posljednje osvježavanje: {{ $now->format('d.m.Y H:i:s') }}
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-xs text-gray-600 dark:border-white/10 dark:bg-gray-900/60 dark:text-gray-300">
            <span class="font-semibold text-gray-900 dark:text-white">Legenda:</span>
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="heroicon-o-queue-list" class="h-4 w-4" /> Regularna stavka
            </span>
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="heroicon-o-calendar-days" class="h-4 w-4 text-info-600" /> Zakazana emisija
            </span>
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="heroicon-o-musical-note" class="h-4 w-4 text-primary-600" /> Tip medija
            </span>
        </div>

        @if (count($warnings))
            <div class="rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm text-warning-800 dark:border-warning-800 dark:bg-warning-950/30 dark:text-warning-200">
                @foreach ($warnings as $warning)
                    <p>{{ $warning }}</p>
                @endforeach
            </div>
        @endif

        @if ($current)
            @php
                $currentType = $typePresentation($current['type']);
            @endphp
            <div class="relative overflow-hidden rounded-2xl border-2 border-success-500 bg-success-50 p-5 shadow-sm dark:bg-success-950/20">
                <div class="absolute inset-y-0 left-0 w-1 bg-success-500"></div>
                <div class="flex flex-col gap-5 pl-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-success-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wide text-success-800 dark:bg-success-900/50 dark:text-success-200">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-success-600"></span>
                                Trenutno u etru
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $currentType['classes'] }}">
                                <x-filament::icon :icon="$currentType['icon']" class="h-3.5 w-3.5" />
                                {{ $currentType['label'] }}
                            </span>
                        </div>
                        <div class="truncate text-lg font-semibold text-gray-950 dark:text-white">{{ $current['title'] }}</div>
                        <div class="truncate text-sm text-gray-600 dark:text-gray-300">{{ $current['artist'] ?: 'StandardClassic' }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-5 text-left sm:min-w-64 sm:text-right">
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Početak</div>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $current['starts_at']?->format('H:i:s') ?: 'Nepoznato' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $current['starts_at']?->format('d.m.Y') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Kraj</div>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $current['ends_at']?->format('H:i:s') ?: 'Nepoznato' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $formatDuration($current['duration']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-white/10">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Predstojeće stavke</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Vrijeme je prikazano prema očekivanom početku emitovanja.</p>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $upcoming->count() }} stavki</span>
            </div>

            @if ($upcoming->isEmpty())
                <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">Nema stavki za prikaz.</div>
            @else
                <div class="relative space-y-3 p-4 sm:p-5">
                    <div class="absolute bottom-5 left-[8.75rem] top-5 z-0 hidden w-px bg-gray-200 md:block dark:bg-white/10"></div>

                    @foreach ($upcoming as $row)
                        @php
                            $type = $typePresentation($row['type']);
                            $source = $sourcePresentation($row['kind']);
                            $status = $statusPresentation($row['status']);
                            $isInterrupted = $row['status'] === 'interrupted';
                            $cardClasses = $row['kind'] === 'scheduled'
                                ? 'border-info-200 bg-info-50 dark:border-info-800/60 dark:bg-info-950'
                                : 'border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900';
                        @endphp

                        <article class="relative z-10 grid gap-4 rounded-xl border p-4 shadow-sm transition-shadow hover:shadow-md md:grid-cols-[7.5rem_2.5rem_minmax(0,1fr)] md:items-center {{ $cardClasses }}">
                            <div class="text-left md:pr-2 md:text-right">
                                <div class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $row['starts_at']?->format('H:i') ?: '—' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $row['starts_at']?->format('d.m.Y') ?: 'Datum nepoznat' }}</div>
                            </div>

                            <div class="relative z-10 hidden h-10 w-10 items-center justify-center rounded-full bg-white ring-4 ring-white md:flex dark:bg-gray-900 dark:ring-gray-900">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full {{ $source['classes'] }} ring-1">
                                    <x-filament::icon :icon="$source['icon']" class="h-4 w-4" />
                                </span>
                            </div>

                            <div class="min-w-0">
                                <div class="mb-2 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $source['classes'] }}" title="Izvor stavke">
                                        <x-filament::icon :icon="$source['icon']" class="h-3.5 w-3.5" />
                                        {{ $source['label'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $type['classes'] }}" title="Tip medija">
                                        <x-filament::icon :icon="$type['icon']" class="h-3.5 w-3.5" />
                                        {{ $type['label'] }}
                                    </span>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $status['classes'] }}">{{ $status['label'] }}</span>
                                </div>

                                <div class="truncate text-base font-semibold text-gray-950 dark:text-white">{{ $row['title'] }}</div>
                                <div class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $row['artist'] ?: $source['label'] }}</div>

                                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-600 dark:text-gray-300">
                                    <span class="inline-flex items-center gap-1.5 font-medium">
                                        <x-filament::icon icon="heroicon-o-play" class="h-3.5 w-3.5 text-gray-400" />
                                        Počinje {{ $row['starts_at']?->format('H:i:s') ?: 'nepoznato' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 font-medium">
                                        <x-filament::icon icon="heroicon-o-flag" class="h-3.5 w-3.5 text-gray-400" />
                                        Do {{ $row['ends_at']?->format('H:i:s') ?: 'nepoznato' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-filament::icon icon="heroicon-o-clock" class="h-3.5 w-3.5 text-gray-400" />
                                        {{ $formatDuration($row['duration']) }}
                                    </span>
                                </div>

                                @if ($isInterrupted)
                                    <div class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-warning-700 dark:text-warning-300">
                                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-3.5 w-3.5" />
                                        Stavka se završava kada počne zakazana emisija.
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
