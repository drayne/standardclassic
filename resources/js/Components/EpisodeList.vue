<script setup lang="ts">
import EpisodeItem from '@/Components/EpisodeItem.vue'
import Pagination from '@/Components/Pagination.vue'
import { onBeforeUnmount, ref, watch } from 'vue'

const props = defineProps<{
    episodes: {
        data: Array<{
            id: number
            title: string
            date: string
            audio_url: string | null
            video_url: string | null
            is_youtube: boolean
            summary: string
            playing: boolean
        }>
        links: Array<{
            url: string | null
            label: string
            active: boolean
        }>
    }
}>()

const localEpisodes = ref(props.episodes)

onBeforeUnmount(() => {
    const audioElements = document.querySelectorAll('audio')
    audioElements.forEach((el) => {
        el.pause()
        el.src = '' // Oslobađanje resursa
        el.load()
    })
})

watch(
    () => props.episodes,
    (newEpisodes) => {
        localEpisodes.value = newEpisodes
    },
)

const togglePlay = async (id: number) => {
    const audioElements = document.querySelectorAll('audio')
    const clickedAudio = document.getElementById(`audio-${id}`) as HTMLAudioElement

    // Pauziraj sve ostale
    audioElements.forEach((el) => {
        if (el !== clickedAudio) {
            el.pause()
        }
    })

    if (!clickedAudio) return

    try {
        if (clickedAudio.paused) {
            await clickedAudio.play()
        } else {
            clickedAudio.pause()
        }
    } catch (error) {
        if (error instanceof Error && error.name !== 'AbortError') {
            console.error('Audio playback error:', error)
        }
    }
}

const onPlay = (id: number) => {
    localEpisodes.value.data.forEach((e) => (e.playing = e.id === id))
}

const onPause = (id: number) => {
    const emission = localEpisodes.value.data.find((e) => e.id === id)
    if (emission) emission.playing = false
}
</script>

<template>
    <div v-if="localEpisodes.data.length" class="flex flex-col gap-8">
        <EpisodeItem
            v-for="episode in localEpisodes.data"
            :key="episode.id"
            :episode="episode"
            @toggle-play="togglePlay"
            @play="onPlay"
            @pause="onPause" />

        <div v-if="localEpisodes.links.length > 3" class="mt-12 flex justify-center">
            <Pagination :links="localEpisodes.links" />
        </div>
    </div>
    <div v-else class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
        <p class="text-gray-500 italic">Trenutno nema dostupnih epizoda u arhivi.</p>
    </div>
</template>
