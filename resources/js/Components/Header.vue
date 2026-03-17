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
                            {{ radioStore.currentTrack.artist }}
                            <span
                                v-if="radioStore.currentTrack.title"
                                class="ml-2 text-xs font-normal text-gray-600 italic"
                                >{{ radioStore.currentTrack.title }}</span
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
                    <div class="relative flex items-center gap-3">
                        <button
                            @click="radioStore.togglePlay"
                            class="flex w-[160px] items-center justify-center gap-2 rounded-full bg-[#b31b1b] px-5 py-1 text-base font-medium text-white transition duration-300 hover:opacity-80 hover:cursor-pointer"
                        >
                            <span
                                v-if="!radioStore.isPlaying"
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
                            v-if="radioStore.isPlaying"
                            class="absolute top-full left-1/2 mt-1 flex -translate-x-1/2 items-center gap-2 rounded-lg bg-white p-2 transition-opacity duration-300 z-50"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="cursor-pointer transition hover:text-red-700"
                                :class="
                                    radioStore.isMuted ||
                                    radioStore.volume === 0
                                        ? 'text-red-700'
                                        : 'text-gray-500'
                                "
                                @click="radioStore.toggleMute"
                            >
                                <polygon
                                    points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"
                                ></polygon>
                                <template
                                    v-if="
                                        !radioStore.isMuted &&
                                        radioStore.volume > 0
                                    "
                                >
                                    <path
                                        d="M19.07 4.93a10 10 0 0 1 0 14.14"
                                    ></path>
                                    <path
                                        d="M15.54 8.46a5 5 0 0 1 0 7.07"
                                    ></path>
                                </template>
                                <line
                                    v-else
                                    x1="23"
                                    y1="9"
                                    x2="17"
                                    y2="15"
                                ></line>
                                <line
                                    v-if="
                                        radioStore.isMuted ||
                                        radioStore.volume === 0
                                    "
                                    x1="17"
                                    y1="9"
                                    x2="23"
                                    y2="15"
                                ></line>
                            </svg>
                            <input
                                type="range"
                                min="0"
                                max="1"
                                step="0.01"
                                v-model="radioStore.volume"
                                @input="
                                    radioStore.updateVolume(radioStore.volume)
                                "
                                class="h-1.5 w-24 cursor-pointer appearance-none rounded-lg bg-gray-200 accent-red-700"
                            />
                        </div>
                    </div>
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

<script setup>
import { radioStore } from '@/stores/radio'
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, watch } from 'vue'

const page = usePage()
const streamUrl = computed(() => page.props.radio?.streamUrl)

watch(
    streamUrl,
    (newUrl) => {
        if (newUrl) {
            radioStore.init(newUrl)
        }
    },
    { immediate: true },
)

let intervalId = null

onMounted(() => {
    radioStore.fetchCurrentTrack()
    // Osvježavaj svakih 10 sekundi
    intervalId = setInterval(() => radioStore.fetchCurrentTrack(), 10000)
})

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId)
})
</script>
