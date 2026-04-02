<template>
    <header class="bg-white py-2">
        <div class="mx-auto max-w-6xl px-4">
            <!-- Jezici na vrhu za mobilni i desktop -->
            <div class="flex justify-end gap-4 text-xs font-medium lowercase py-1">
                <button
                    @click="setLang('en')"
                    class="transition hover:text-red-700 hover:cursor-pointer"
                    :class="{ 'font-bold text-red-700 underline': currentLang === 'en' }">
                    english
                </button>
                <button
                    @click="setLang('de')"
                    class="transition hover:text-red-700 hover:cursor-pointer"
                    :class="{ 'font-bold text-red-700 underline': currentLang === 'de' }">
                    deutsch
                </button>
                <button
                    @click="setLang('sr')"
                    class="transition hover:text-red-700 hover:cursor-pointer"
                    :class="{ 'font-bold text-red-700 underline': currentLang === 'sr' }">
                    srpski
                </button>
            </div>

            <div class="flex flex-col lg:flex-row items-center lg:items-end justify-between">
                <!-- Logo -->
                <div class="pb-4 lg:pb-8 shrink-0">
                    <Link href="/" class="block">
                        <img src="/images/logo.png" alt="Standard Classic Radio" class="h-24 lg:h-34 w-auto" />
                    </Link>
                </div>

                <!-- Desni blok -->
                <div class="flex w-full flex-col items-center lg:items-end gap-4 pb-4 lg:pb-8">
                    <!-- Srednji red: Trenutno na programu i dugme -->
                    <div
                        class="flex w-full flex-col lg:flex-row items-center justify-center lg:justify-end gap-6 lg:gap-8 pt-2 lg:pt-5">
                        <div
                            class="lg:border-b-0 lg:border-r border-gray-200 pb-4 lg:pb-0 lg:pr-8 text-center lg:text-right leading-tight w-full lg:w-auto">
                            <p class="text-[10px] tracking-wider text-gray-500 uppercase">
                                {{ t('currently_on_air') }}:
                            </p>
                            <p class="text-sm font-bold">
                                {{ radioStore.currentTrack.composer_name }}
                                <span
                                    v-if="radioStore.currentTrack.title"
                                    class="ml-2 text-xs font-normal text-gray-600 italic">
                                    {{ radioStore.currentTrack.title }}
                                </span>
                                <span
                                    v-if="radioStore.currentTrack.artist"
                                    class="text-xs font-normal text-gray-400 italic">
                                    ({{ radioStore.currentTrack.artist }})
                                </span>
                            </p>
                        </div>

                        <div class="relative flex items-center gap-3">
                            <button
                                @click="radioStore.togglePlay"
                                class="flex w-40 items-center justify-center gap-2 rounded-full bg-radio-red px-5 py-1 text-base font-medium text-white transition duration-300 hover:opacity-80 hover:cursor-pointer">
                                <span v-if="!radioStore.isPlaying" class="flex items-center gap-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <span class="pb-0.5">{{ t('listen_live') }}</span>
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
                                        stroke-linejoin="round">
                                        <rect x="6" y="4" width="4" height="16"></rect>
                                        <rect x="14" y="4" width="4" height="16"></rect>
                                    </svg>
                                    <span class="pb-0.5">{{ t('pause') }}</span>
                                </span>
                            </button>

                            <div
                                v-if="radioStore.isPlaying"
                                class="absolute bottom-full left-1/2 flex -translate-x-1/2 items-center z-50">
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
                                            radioStore.isMuted || radioStore.volume === 0
                                                ? 'text-red-700'
                                                : 'text-gray-400'
                                        "
                                        @click="radioStore.toggleMute"
                                        @mouseover="showVolumeSlider = true">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                        <template v-if="!radioStore.isMuted && radioStore.volume > 0">
                                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                                            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                        </template>
                                        <line v-else x1="23" y1="9" x2="17" y2="15"></line>
                                        <line
                                            v-if="radioStore.isMuted || radioStore.volume === 0"
                                            x1="17"
                                            y1="9"
                                            x2="23"
                                            y2="15"></line>
                                    </svg>
                                    <input
                                        type="range"
                                        min="0"
                                        max="1"
                                        step="0.01"
                                        v-model="radioStore.volume"
                                        @input="radioStore.updateVolume(radioStore.volume)"
                                        @mouseout="showVolumeSlider = false"
                                        class="h-1.5 cursor-pointer appearance-none rounded-lg bg-gray-200 accent-red-700 transition-all duration-400"
                                        :class="
                                            showVolumeSlider ? 'w-24 opacity-100' : 'w-0 opacity-0 pointer-events-none'
                                        " />
                                </div>
                            </div>
                        </div>
                    </div>

                    <nav class="hidden items-center gap-4 xl:gap-8 lg:flex pt-3 font-medium capitalize">
                        <Link
                            :href="route('program-radija')"
                            :active="route().current('program-radija')"
                            class="text-sm xl:text-base transition hover:text-red-700 active:text-red-700">
                            {{ t('program') }}
                        </Link>
                        <Link
                            :href="route('emisije-na-nasem-radiju')"
                            class="text-sm xl:text-base transition hover:text-red-700">
                            {{ t('shows') }}
                        </Link>
                        <Link
                            :href="route('podkasti-na-standardclassic-radiju')"
                            class="text-sm xl:text-base transition hover:text-red-700">
                            {{ t('podcasts') }}
                        </Link>
                        <Link
                            :href="route('zasto-postojimo')"
                            class="text-sm xl:text-base transition hover:text-red-700">
                            {{ t('why_we_exist') }}
                        </Link>
                        <Link
                            :href="route('vijesti-iz-kulture')"
                            class="text-sm xl:text-base transition hover:text-red-700">
                            {{ t('culture_news') }}
                        </Link>
                        <Link
                            :href="route('vijesti-iz-dnevno-politickog-zivota')"
                            class="text-sm xl:text-base transition hover:text-red-700">
                            {{ t('politics_news') }}
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { useTrans } from '@/Composables/useTrans'
import { radioStore } from '@/stores/radio'
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

const { t, setLang, currentLang } = useTrans()
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
