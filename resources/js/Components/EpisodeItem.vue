<script setup lang="ts">
const props = defineProps<{
    episode: {
        id: number
        title: string
        date: string
        audio_url: string | null
        video_url: string | null
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

const togglePlay = () => {
    emit('toggle-play', props.episode.id)
}
</script>

<template>
    <div
        @click="!episode.is_youtube && togglePlay()"
        :class="[
            'group relative flex flex-col bg-white border rounded-xl p-4 md:p-5 transition-all duration-500 overflow-hidden',
            !episode.is_youtube ? 'cursor-pointer' : '',
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

        <!-- Background Indicator (Play/Pause for Audio) -->
        <button
            v-if="!episode.is_youtube"
            @click.stop="togglePlay"
            type="button"
            class="absolute right-4 top-4 md:right-auto md:-left-2 md:top-1/2 md:-translate-y-1/2 transition-all duration-500 group-hover:scale-105 z-30 focus:outline-none cursor-pointer"
            :class="[
                episode.playing
                    ? 'text-radio-red scale-110 opacity-[0.2]'
                    : 'text-gray-400 group-hover:text-radio-red/30 opacity-[0.08] group-hover:opacity-[0.25] hover:opacity-[0.5]',
            ]">
            <svg
                v-if="!episode.playing"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="w-24 h-24 md:w-32 md:h-32">
                <path
                    fill-rule="evenodd"
                    d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z"
                    clip-rule="evenodd" />
            </svg>
            <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="w-24 h-24 md:w-32 md:h-32">
                <path
                    fill-rule="evenodd"
                    d="M8.5 5a.5.5 0 01.5.5v13a.5.5 0 01-1 0v-13a.5.5 0 01.5-.5zM15.5 5a.5.5 0 01.5.5v13a.5.5 0 01-1 0v-13a.5.5 0 01.5-.5z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <!-- YouTube Indicator (Mobile watermark) -->
        <div
            v-else
            class="absolute right-4 top-4 md:hidden pointer-events-none transition-all duration-500 opacity-[0.08] text-radio-red/60">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-24 h-24">
                <path d="M4.5 4.5a3 3 0 00-3 3v9a3 3 0 003 3h15a3 3 0 003-3v-9a3 3 0 00-3-3h-15zm6 4.5l5 3-5 3V9z" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col gap-4" :class="{ 'md:pl-24': !episode.is_youtube }">
            <!-- Top Row: Title/Date -->
            <div class="flex flex-row items-center gap-4">
                <!-- YouTube Icon Visual remains for video (Desktop) -->
                <div v-if="episode.is_youtube" class="hidden md:flex flex-shrink-0">
                    <div
                        class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center bg-radio-red/10 text-radio-red">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-6 h-6 md:w-8 md:h-8">
                            <path
                                d="M4.5 4.5a3 3 0 00-3 3v9a3 3 0 003 3h15a3 3 0 003-3v-9a3 3 0 00-3-3h-15zm6 4.5l5 3-5 3V9z" />
                        </svg>
                    </div>
                </div>

                <!-- Title & Date -->
                <div
                    class="flex-grow flex flex-col md:flex-row md:items-start md:justify-between min-w-0 gap-1 md:gap-4">
                    <div class="order-2 md:order-1 flex flex-col min-w-0 relative pt-4">
                        <!-- Now Playing Indicator -->
                        <div
                            v-if="episode.playing && !episode.is_youtube"
                            class="absolute top-0 left-0 flex items-center gap-1.5 text-radio-red text-[10px] font-bold uppercase tracking-widest animate-pulse">
                            <span class="flex h-1.5 w-1.5 rounded-full bg-radio-red"></span>
                            Slušate sada
                        </div>

                        <h3
                            class="font-bold text-base md:text-xl text-gray-900 group-hover:text-radio-red transition-colors leading-tight line-clamp-2">
                            {{ episode.title }}
                        </h3>
                        <!-- Summary next to title on desktop, below on mobile -->
                        <p
                            class="text-gray-600 group-hover:text-radio-red/60 text-xs md:text-sm leading-relaxed line-clamp-2 mt-1 transition-colors">
                            {{ episode.summary }}
                        </p>
                    </div>
                    <span
                        class="order-1 md:order-2 inline-flex items-center self-start md:self-start px-2.5 py-1 rounded-full text-[10px] md:text-xs font-semibold bg-gray-50 text-gray-500 border border-gray-100 flex-shrink-0 transition-colors group-hover:border-radio-red/20 group-hover:bg-radio-red/5 group-hover:text-radio-red">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-3 w-3 mr-1 text-gray-400 group-hover:text-radio-red/50 transition-colors"
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
                    <div v-else class="mt-2 bg-white rounded-full border border-gray-300" @click.stop>
                        <audio
                            :id="`audio-${episode.id}`"
                            controls
                            preload="auto"
                            controlsList="nodownload"
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
