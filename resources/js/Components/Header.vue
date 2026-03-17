<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref } from 'vue'

const page = usePage()
const streamUrl = computed(() => page.props.radio?.streamUrl)

const currentTrack = ref({
    title: 'Učitavanje...',
    artist: '',
})

const isPlaying = ref(false)
const audioRef = ref(null)
const volume = ref(
    localStorage.getItem('radioVolume')
        ? parseFloat(localStorage.getItem('radioVolume'))
        : 0.7,
)

const updateVolume = () => {
    if (audioRef.value) {
        audioRef.value.volume = volume.value
        localStorage.setItem('radioVolume', volume.value.toString())
    }
}

const togglePlay = () => {
    if (!audioRef.value) return

    if (isPlaying.value) {
        audioRef.value.pause()
        // Da bismo izbjegli kašnjenje pri ponovnom pokretanju (live stream),
        // resetiramo izvor kako bi učitao svježi buffer kad se ponovno pokrene
        audioRef.value.src = ''
        audioRef.value.load()
        isPlaying.value = false
    } else {
        audioRef.value.src = streamUrl.value
        audioRef.value
            .play()
            .then(() => {
                updateVolume()
            })
            .catch((error) => {
                console.error('Greška pri pokretanju radija:', error)
            })
        isPlaying.value = true
    }
}

const fetchCurrentTrack = async () => {
    try {
        const response = await fetch('/api/radio/current')
        if (response.ok) {
            currentTrack.value = await response.json()
        }
    } catch (error) {
        console.error('Greška pri dohvaćanju trenutne pjesme:', error)
    }
}

let intervalId = null

onMounted(() => {
    fetchCurrentTrack()
    // Osvježavaj svakih 10 sekundi
    intervalId = setInterval(fetchCurrentTrack, 10000)

    // Inicijalizuj jačinu zvuka ako je plejer već aktivan (mada se u Headeru ponovo kreira pri reloadu)
    if (audioRef.value) {
        audioRef.value.volume = volume.value
    }
})

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId)
})
</script>

<template>
    <header class="bg-white py-6">
        <div class="mx-auto flex max-w-6xl items-end justify-between px-4">
            <!-- Logo s leve strane -->
            <div class="flex-shrink-0">
                <Link href="/" class="block">
                    <img
                        src="/images/logo.png"
                        alt="Standard Classic Radio"
                        class="h-28 w-auto"
                    />
                </Link>
            </div>

            <!-- Desni blok sa dva reda -->
            <div class="flex w-full flex-col items-end gap-4">
                <!-- Gornji red: Jezici i Trenutno na programu -->
                <div class="flex w-full items-center justify-end gap-8 pb-2">
                    <div
                        class="border-r border-gray-200 pr-8 text-right leading-tight"
                    >
                        <p
                            class="text-[10px] tracking-wider text-gray-500 uppercase"
                        >
                            Trenutno na programu:
                        </p>
                        <p class="text-sm font-bold">
                            {{ currentTrack.artist }}
                            <span
                                v-if="currentTrack.title"
                                class="ml-2 text-xs font-normal text-gray-600 italic"
                                >{{ currentTrack.title }}</span
                            >
                        </p>
                    </div>

                    <div class="flex gap-4 text-xs font-medium lowercase">
                        <button class="transition hover:text-red-700">
                            english
                        </button>
                        <button class="transition hover:text-red-700">
                            deutsch
                        </button>
                        <button class="font-bold text-red-700 underline">
                            srpski
                        </button>
                    </div>
                </div>

                <!-- Donji red: Navigacioni meni -->
                <nav class="hidden items-center gap-8 lg:flex">
                    <Link
                        :href="route('program-radija')"
                        class="text-base font-normal transition hover:text-red-700"
                        >program</Link
                    >
                    <Link
                        href="#"
                        class="text-base font-normal transition hover:text-red-700"
                        >emisije</Link
                    >
                    <Link
                        href="#"
                        class="text-base font-normal transition hover:text-red-700"
                        >podkasti</Link
                    >
                    <Link
                        :href="route('zasto-postojimo')"
                        class="text-base font-normal transition hover:text-red-700"
                        >zašto postojimo</Link
                    >
                    <div class="flex items-center gap-3">
                        <button
                            @click="togglePlay"
                            class="flex min-w-[140px] items-center justify-center gap-2 rounded-full bg-[#b31b1b] px-5 py-1 text-base font-medium text-white transition duration-300 hover:opacity-80"
                        >
                            <span
                                v-if="!isPlaying"
                                class="flex items-center gap-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                                slušaj uživo
                            </span>
                            <span v-else class="flex items-center gap-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                </svg>
                                pauziraj
                            </span>
                        </button>

                        <div
                            v-if="isPlaying"
                            class="flex items-center gap-2 transition-opacity duration-300"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="text-gray-500"
                            >
                                <polygon
                                    points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"
                                ></polygon>
                                <path
                                    d="M19.07 4.93a10 10 0 0 1 0 14.14"
                                ></path>
                                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                            </svg>
                            <input
                                type="range"
                                min="0"
                                max="1"
                                step="0.01"
                                v-model="volume"
                                @input="updateVolume"
                                class="h-1.5 w-20 cursor-pointer appearance-none rounded-lg bg-gray-200 accent-red-700"
                            />
                        </div>
                    </div>
                    <audio ref="audioRef" preload="none"></audio>
                </nav>
            </div>
        </div>
    </header>
</template>

<style scoped>
/* Ako želiš da font bude identičan onom sa slike (Serif stil),
   možeš dodati font-family ovde ili u Tailwind config */
nav a {
    font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;
}
</style>
