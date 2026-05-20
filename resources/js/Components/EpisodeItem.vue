<script setup lang="ts">
const props = defineProps<{
    episode: {
        id: number
        title: string
        date: string
        audio_url: string | null
        video_url: string | null
        file_size: number | null
        is_youtube: boolean
        summary: string
        playing: boolean
    }
}>()

const emit = defineEmits<{
    (e: 'toggle-play', id: number): void
    (e: 'play', id: number): void
    (e: 'pause', id: number): void
}>()

const onPlay = () => {
    emit('play', props.episode.id)
}

const onPause = () => {
    emit('pause', props.episode.id)
}
</script>

<template>
    <div
        :class="[
            'group relative flex flex-col bg-white border rounded-xl p-4 md:px-8 md:py-5 transition-all duration-500 overflow-hidden',
            episode.playing
                ? 'border-radio-red/40 shadow-[0_12px_30px_rgba(179,27,27,0.1)] ring-1 ring-radio-red/10 bg-radio-red/[0.02]'
                : 'border-gray-100 hover:border-radio-red/30 hover:shadow-xl hover:bg-radio-red/[0.02]',
        ]">
        <!-- Creative Accent Strip -->
        <div
            class="absolute left-0 top-0 bottom-0 w-[3px] transition-all duration-500 z-20"
            :class="
                episode.playing
                    ? 'bg-radio-red shadow-[0_0_15px_rgba(179,27,27,0.5)]'
                    : 'bg-transparent group-hover:bg-radio-red/20'
            ">
        </div>

        <!-- Background Soundwave Texture -->
        <div
            v-if="!episode.is_youtube"
            class="absolute right-0 bottom-0 pointer-events-none opacity-[0.03] transition-all duration-1000"
            :class="{ 'opacity-[0.1] scale-110 translate-x-4': episode.playing }">
            <svg
                width="300"
                height="120"
                viewBox="0 0 300 120"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="text-radio-red">
                <path
                    d="M0 60C30 40 60 80 90 60C120 40 150 80 180 60C210 40 240 80 270 60C300 40 330 80 360 60"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    class="path-animate" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col gap-4 pt-3">
            <!-- Top Row: Layout with Content on left and Download on right -->
            <div class="flex flex-row items-start gap-4 md:gap-8">
                <!-- Left Column: Date, Title, Summary -->
                <div class="flex-grow flex flex-col min-w-0">
                    <div class="relative">
                        <!-- Now Playing Indicator -->
                        <div
                            v-if="episode.playing && !episode.is_youtube"
                            class="absolute -top-5 left-0 flex items-center gap-1.5 text-radio-red text-[10px] font-bold uppercase tracking-widest animate-pulse">
                            <span class="flex h-1.5 w-1.5 rounded-full bg-radio-red"></span>
                            Slušate sada
                        </div>

                        <!-- Datum -->
                        <div class="flex items-center mb-2">
                            <span
                                class="inline-flex items-center text-[10px] md:text-xs font-medium text-gray-500 transition-colors">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3 w-3 mr-1 text-gray-400 transition-colors"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ episode.date }}
                            </span>
                        </div>

                        <h3
                            class="font-bold text-base md:text-xl text-gray-900 transition-colors leading-tight line-clamp-2">
                            {{ episode.title }}
                        </h3>
                        <p class="text-gray-600 text-xs md:text-sm leading-relaxed line-clamp-2 mt-1 transition-colors">
                            {{ episode.summary }}
                        </p>
                    </div>
                </div>

                <!-- Right Column: Preuzmi dugme -->
                <div v-if="!episode.is_youtube && episode.audio_url" class="flex-shrink-0 pt-0.5">
                    <a
                        :href="episode.audio_url"
                        download
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1 bg-gray-50 hover:bg-radio-red hover:text-white text-gray-500 hover:border-radio-red/20 text-[10px] font-bold uppercase tracking-wider rounded-full border border-gray-100 transition-all duration-300 group/download"
                        title="Preuzmi emisiju"
                        @click.stop>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 md:h-3 md:w-3 transition-transform group-hover/download:translate-y-0.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span class="hidden md:inline">Preuzmi</span>
                        <span
                            v-if="episode.file_size"
                            class="text-[9px] text-gray-400 group-hover/download:text-white/70 font-normal normal-case ml-0.5">
                            ({{ episode.file_size }} MB)
                        </span>
                    </a>
                </div>
            </div>

            <!-- Bottom Section: Player -->
            <div class="flex flex-col w-full">
                <div class="w-full">
                    <div
                        v-if="episode.is_youtube"
                        class="relative w-full aspect-video rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                        <iframe
                            :src="episode.video_url || ''"
                            class="absolute top-0 left-0 w-full h-full"
                            frameborder="0"
                            allow="
                                accelerometer;
                                autoplay;
                                clipboard-write;
                                encrypted-media;
                                gyroscope;
                                picture-in-picture;
                                web-share;
                            "
                            allowfullscreen></iframe>
                    </div>
                    <div v-else class="mt-2 flex flex-col items-center gap-3">
                        <div class="flex-grow w-full bg-white rounded-full border border-gray-300">
                            <audio
                                :id="`audio-${episode.id}`"
                                controls
                                preload="auto"
                                class="w-full h-10 md:h-12 custom-audio-player"
                                @play="onPlay"
                                @pause="onPause"
                                @ended="onPause">
                                <source :src="episode.audio_url || ''" type="audio/mpeg" />
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Stilizacija za audio plejer - boje aplikacije */
.custom-audio-player {
    accent-color: #b31b1b;
    filter: opacity(0.8);
    background-color: white; /* Beli pokrivač za sakrivanje sive pozadine browsera */
    border-radius: 9999px;
    transition: all 0.3s ease;
}

/* Chrome/Safari specifičan CSS za uklanjanje podrazumevane sive pozadine */
.custom-audio-player::-webkit-media-controls-enclosure {
    background-color: white !important;
}

.custom-audio-player::-webkit-media-controls-panel {
    background-color: white !important;
}

.custom-audio-player:hover {
    filter: opacity(1);
}

/* Animacija za talas u pozadini */
@keyframes wave-flow {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-60px);
    }
}

.path-animate {
    animation: wave-flow 4s linear infinite;
}
</style>
