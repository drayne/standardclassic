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
        class="group flex flex-col bg-white border border-gray-100 rounded-xl p-4 md:p-5 hover:border-radio-red/30 hover:shadow-xl transition-all duration-300">
        <div class="flex flex-col gap-4">
            <!-- Top Row: Play Button + Title/Date -->
            <div class="flex flex-row items-center gap-4">
                <!-- Play/Video Icon Visual -->
                <div v-if="!episode.is_youtube" class="flex-shrink-0">
                    <button
                        @click="togglePlay"
                        class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-sm"
                        :class="
                            episode.playing
                                ? 'bg-radio-red text-white'
                                : 'bg-radio-red/10 text-radio-red group-hover:bg-radio-red group-hover:text-white'
                        ">
                        <svg
                            v-if="!episode.playing"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-6 h-6 md:w-8 md:h-8 ml-1">
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
                            class="w-6 h-6 md:w-8 md:h-8">
                            <path
                                fill-rule="evenodd"
                                d="M6.75 5.25a.75.75 0 01.75.75v12a.75.75 0 01-1.5 0V6a.75.75 0 01.75-.75zM17.25 5.25a.75.75 0 01.75.75v12a.75.75 0 01-1.5 0V6a.75.75 0 01.75-.75z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div v-else class="flex-shrink-0">
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
                    class="flex-grow flex flex-col md:flex-row md:items-center md:justify-between min-w-0 gap-1 md:gap-4">
                    <div class="order-2 md:order-1 flex flex-col min-w-0">
                        <h3
                            class="font-bold text-base md:text-xl text-gray-900 group-hover:text-radio-red transition-colors leading-tight line-clamp-2">
                            {{ episode.title }}
                        </h3>
                        <!-- Summary next to title on desktop, below on mobile -->
                        <p class="text-gray-600 text-xs md:text-sm leading-relaxed line-clamp-2 mt-1">
                            {{ episode.summary }}
                        </p>
                    </div>
                    <span
                        class="order-1 md:order-2 inline-flex items-center self-start md:self-center px-2 py-0.5 rounded-full text-[10px] md:text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200 flex-shrink-0">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-3 w-3 mr-1 text-gray-400"
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
                    <div v-else class="mt-2 bg-white rounded-full border border-gray-300">
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
/* Stilizacija za audio plejer - smanjen naglasak */
.custom-audio-player {
    filter: grayscale(40%) opacity(0.7) contrast(95%);
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
    filter: grayscale(0%) opacity(1) contrast(100%);
}
</style>
