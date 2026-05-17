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
        class="group flex flex-col bg-white border border-gray-100 rounded-xl p-5 hover:border-radio-red/30 hover:shadow-xl transition-all duration-300">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Play/Video Icon Visual -->
            <div v-if="!episode.is_youtube" class="flex-shrink-0 flex items-center justify-center">
                <button
                    @click="togglePlay"
                    class="w-16 h-16 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-sm"
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
                        class="w-8 h-8 ml-1">
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
                        class="w-8 h-8">
                        <path
                            fill-rule="evenodd"
                            d="M6.75 5.25a.75.75 0 01.75.75v12a.75.75 0 01-1.5 0V6a.75.75 0 01.75-.75zM17.25 5.25a.75.75 0 01.75.75v12a.75.75 0 01-1.5 0V6a.75.75 0 01.75-.75z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div v-else class="flex-shrink-0 flex items-center justify-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center bg-radio-red/10 text-radio-red">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                        <path
                            d="M4.5 4.5a3 3 0 00-3 3v9a3 3 0 003 3h15a3 3 0 003-3v-9a3 3 0 00-3-3h-15zm6 4.5l5 3-5 3V9z" />
                    </svg>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-grow flex flex-col">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                    <h3 class="font-bold text-xl text-gray-900 group-hover:text-radio-red transition-colors">
                        {{ episode.title }}
                    </h3>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1"
                            fill="none"
                            viewBox="0 0 26 26"
                            stroke="black">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ episode.date }}
                    </span>
                </div>

                <p class="text-gray-600 text-base leading-relaxed mb-4 line-clamp-2 md:line-clamp-none">
                    {{ episode.summary }}
                </p>

                <div class="w-full mt-auto pt-2">
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
                    <audio
                        v-else
                        :id="`audio-${episode.id}`"
                        controls
                        preload="auto"
                        controlsList="nodownload"
                        class="w-full h-8 custom-audio-player"
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
</template>

<style scoped>
/* Stilizacija za audio plejer - smanjen naglasak */
.custom-audio-player {
    filter: grayscale(100%) opacity(0.6) contrast(90%);
    border-radius: 8px;
    transition: opacity 0.3s ease;
}

.custom-audio-player:hover {
    opacity: 1;
}
</style>
