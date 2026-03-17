<template>
    <header class="bg-white py-2">
        <div class="mx-auto flex max-w-6xl items-end justify-between px-4">
            <div class="pb-6 shrink-0">
                <Link href="/" class="block">
                    <img
                        src="/images/logo.png"
                        alt="Standard Classic Radio"
                        class="h-32 w-auto"
                    />
                </Link>
            </div>

            <!-- Desni blok sa dva reda -->
            <div class="flex w-full flex-col items-end gap-4 pb-8">
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

                <!-- Gornji red: Trenutno na programu -->
                <div
                    class="flex w-full items-center justify-end gap-8 pb-2 pt-5"
                >
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
                                <span class="pb-1">slušaj uživo</span>
                            </span>
                            <span v-else class="flex items-center gap-2">
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
                                >
                                    <rect
                                        x="6"
                                        y="4"
                                        width="4"
                                        height="16"
                                    ></rect>
                                    <rect
                                        x="14"
                                        y="4"
                                        width="4"
                                        height="16"
                                    ></rect>
                                </svg>
                                <span class="pb-1">pauziraj</span>
                            </span>
                        </button>

                        <div
                            v-if="radioStore.isPlaying"
                            class="absolute bottom-full left-1/2 flex -translate-x-1/2 items-center z-50"
                        >
                            <div class="flex items-center gap-2 rounded-lg p-2">
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
                                            : 'text-gray-400'
                                    "
                                    @click="radioStore.toggleMute"
                                    @mouseover="showVolumeSlider = true"
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
                                        radioStore.updateVolume(
                                            radioStore.volume,
                                        )
                                    "
                                    @mouseout="showVolumeSlider = false"
                                    class="h-1.5 cursor-pointer appearance-none rounded-lg bg-gray-200 accent-red-700 transition-all duration-400"
                                    :class="
                                        showVolumeSlider
                                            ? 'w-24 opacity-100'
                                            : 'w-0 opacity-0 pointer-events-none'
                                    "
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donji red: Navigacioni meni -->
                <nav class="hidden items-center gap-8 lg:flex pt-1">
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
                    <Link
                        :href="route('zasto-postojimo')"
                        class="text-base font-normal transition hover:text-red-700"
                        >vijesti iz kulture</Link
                    >
                    <Link
                        :href="route('zasto-postojimo')"
                        class="text-base font-normal transition hover:text-red-700"
                        >vijesti iz dnevno-političkog života</Link
                    >
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
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

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
const showVolumeSlider = ref(false)

onMounted(() => {
    radioStore.fetchCurrentTrack()
    // Osvježavaj svakih 10 sekundi
    intervalId = setInterval(() => radioStore.fetchCurrentTrack(), 10000)
})

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId)
})
</script>
