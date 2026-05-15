<script setup lang="ts">
import Pagination from '@/Components/Pagination.vue'
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const { t } = useTrans()

defineOptions({ layout: MainLayout })

// Mock podaci za emisije
const emissions = ref({
    data: [
        {
            id: 1,
            title: 'Vijesti sa Dankom - 15.05.2026.',
            date: '15.05.2026',
            audio_url: '#',
            summary:
                'U današnjoj emisiji razgovaramo o najnovijim dešavanjima u regionu, ekonomskim trendovima i kulturnim događajima koji su obilježili dan.',
            playing: false,
        },
        {
            id: 2,
            title: 'Vijesti sa Dankom - 14.05.2026.',
            date: '14.05.2026',
            audio_url: '#',
            summary:
                'Fokus današnje emisije je na novim infrastrukturnim projektima i njihovom uticaju na lokalnu zajednicu, uz osvrt na sportske vijesti.',
            playing: false,
        },
        {
            id: 3,
            title: 'Vijesti sa Dankom - 13.05.2026.',
            date: '13.05.2026',
            audio_url: '#',
            summary:
                'Analiziramo rezultate nedavnih izbora, pratimo promjene na tržištu rada i donosimo priču o uspješnim mladim preduzetnicima.',
            playing: false,
        },
        {
            id: 4,
            title: 'Vijesti sa Dankom - 12.05.2026.',
            date: '12.05.2026',
            audio_url: '#',
            summary:
                'Specijalno izdanje posvećeno ekologiji i održivom razvoju. Kako svako od nas može doprinijeti očuvanju životne sredine?',
            playing: false,
        },
        {
            id: 5,
            title: 'Vijesti sa Dankom - 11.05.2026.',
            date: '11.05.2026',
            audio_url: '#',
            summary:
                'Pregled najvažnijih vijesti iz svijeta nauke i tehnologije, uz intervju sa vodećim stručnjacima u oblasti vještačke inteligencije.',
            playing: false,
        },
    ],
    meta: {
        links: [
            { url: null, label: '&laquo; Previous', active: false },
            { url: '#', label: '1', active: true },
            { url: '#', label: '2', active: false },
            { url: '#', label: '3', active: false },
            { url: null, label: 'Next &raquo;', active: false },
        ],
    },
})

const togglePlay = (id: number) => {
    const audioElements = document.querySelectorAll('audio')
    const clickedAudio = document.getElementById(`audio-${id}`) as HTMLAudioElement

    audioElements.forEach((el) => {
        if (el !== clickedAudio) {
            el.pause()
        }
    })

    if (clickedAudio.paused) {
        clickedAudio.play()
    } else {
        clickedAudio.pause()
    }
}

const onPlay = (id: number) => {
    emissions.value.data.forEach((e) => (e.playing = e.id === id))
}

const onPause = (id: number) => {
    const emission = emissions.value.data.find((e) => e.id === id)
    if (emission) emission.playing = false
}
</script>

<template>
    <Head :title="t('danka_podcast.title')" />

    <div>
        <div class="py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <img src="/images/vijesti-sa-dankom.jpg" :alt="t('danka_podcast.title')" class="h-auto w-full" />

                <div class="flex flex-col gap-5 lg:mt-8">
                    <div class="flex flex-col gap-2">
                        <h1 class="inline-block text-2xl font-medium">{{ t('danka_podcast.title') }}</h1>
                        <p class="text-radio-red mb-3 text-base font-bold italic">{{ t('danka_podcast.author') }}</p>
                    </div>
                    <p class="-mt-2 text-lg leading-relaxed text-gray-700" v-html="t('danka_podcast.p1')"></p>
                </div>
            </div>

            <div class="mt-16">
                <h2 class="mb-8 flex items-center text-lg font-bold tracking-wide uppercase">
                    <span class="bg-radio-red mr-3 h-1 w-8 shrink-0"></span>
                    Arhiva emisija
                </h2>

                <div class="flex flex-col gap-8">
                    <div
                        v-for="emission in emissions.data"
                        :key="emission.id"
                        class="group flex flex-col bg-white border border-gray-100 rounded-xl p-5 hover:border-radio-red/30 hover:shadow-xl transition-all duration-300">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Play Button Visual -->
                            <div class="flex-shrink-0 flex items-center justify-center">
                                <button
                                    @click="togglePlay(emission.id)"
                                    class="w-16 h-16 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-sm"
                                    :class="
                                        emission.playing
                                            ? 'bg-radio-red text-white'
                                            : 'bg-radio-red/10 text-radio-red group-hover:bg-radio-red group-hover:text-white'
                                    ">
                                    <svg
                                        v-if="!emission.playing"
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

                            <!-- Content -->
                            <div class="flex-grow flex flex-col">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                    <h3
                                        class="font-bold text-xl text-gray-900 group-hover:text-radio-red transition-colors">
                                        {{ emission.title }}
                                    </h3>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-3 w-3 mr-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ emission.date }}
                                    </span>
                                </div>

                                <p class="text-gray-600 text-base leading-relaxed mb-4 line-clamp-2 md:line-clamp-none">
                                    {{ emission.summary }}
                                </p>

                                <div class="w-full mt-auto pt-2">
                                    <audio
                                        :id="`audio-${emission.id}`"
                                        controls
                                        class="w-full h-8 custom-audio-player"
                                        @play="onPlay(emission.id)"
                                        @pause="onPause(emission.id)"
                                        @ended="onPause(emission.id)">
                                        <source :src="emission.audio_url" type="audio/mpeg" />
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-center">
                    <Pagination :links="emissions.meta.links" />
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
