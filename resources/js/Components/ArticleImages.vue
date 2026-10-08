<template>
    <div>
        <button
            type="button"
            class="group relative block w-full overflow-hidden rounded-sm shadow-sm"
            :class="hasGallery ? 'hover:cursor-zoom-in' : 'cursor-default'"
            :disabled="!hasGallery"
            :aria-label="hasGallery ? t('gallery.open') : undefined"
            @click="openAt(0)">
            <img :src="images[0]" :alt="alt" class="h-auto w-full object-cover" />
            <span
                v-if="hasGallery"
                class="absolute right-2 bottom-2 flex items-center gap-1.5 rounded-sm bg-black/70 px-2 py-1 text-xs font-bold text-white transition group-hover:bg-radio-red">
                <i class="pi pi-images text-sm" aria-hidden="true"></i>
                {{ images.length }} {{ t('gallery.photos') }}
            </span>
        </button>

        <div v-if="hasGallery" ref="thumbnailRow" class="mt-1.5 flex gap-1.5 overflow-hidden">
            <button
                v-for="(image, index) in thumbnails"
                :key="image"
                type="button"
                class="relative h-10 w-14 shrink-0 overflow-hidden rounded-sm hover:cursor-zoom-in"
                @click="openAt(index + 1)">
                <img :src="image" alt="" class="h-full w-full object-cover transition-transform duration-300 hover:scale-105" />
                <span
                    v-if="index === thumbnails.length - 1 && hiddenCount > 0"
                    class="absolute inset-0 flex items-center justify-center bg-black/60 text-sm font-bold text-white">
                    +{{ hiddenCount }}
                </span>
            </button>
        </div>

        <div v-if="source" class="mt-1 text-right text-xs font-bold text-gray-500 italic">
            {{ t('photo') }}: {{ source }}
        </div>

        <ImageLightbox
            :images="images"
            :open="lightboxOpen"
            :start-index="startIndex"
            :alt="alt"
            :caption="source ? `${t('photo')}: ${source}` : null"
            @close="lightboxOpen = false" />
    </div>
</template>

<script setup lang="ts">
import ImageLightbox from '@/Components/ImageLightbox.vue'
import { useTrans } from '@/Composables/useTrans'
import { computed, onMounted, onUnmounted, ref } from 'vue'

const { t } = useTrans()

// Širina sličice (w-14) i razmak između njih (gap-1.5) u pikselima
const THUMBNAIL_WIDTH = 56
const THUMBNAIL_GAP = 6

const props = withDefaults(
    defineProps<{
        images: string[]
        alt?: string
        source?: string | null
    }>(),
    { alt: '', source: null },
)

const lightboxOpen = ref(false)
const startIndex = ref(0)

const hasGallery = computed(() => props.images.length > 1)
const thumbnailRow = ref<HTMLElement | null>(null)
const visibleThumbnails = ref(4)
const thumbnails = computed(() => props.images.slice(1, 1 + visibleThumbnails.value))
// Na posljednjoj sličici prikazujemo koliko slika nije stalo u red
const hiddenCount = computed(() => props.images.length - 1 - thumbnails.value.length)

// Prikazujemo onoliko sličica koliko stane u jedan red
const updateVisibleThumbnails = () => {
    if (!thumbnailRow.value) return
    const width = thumbnailRow.value.clientWidth
    visibleThumbnails.value = Math.max(1, Math.floor((width + THUMBNAIL_GAP) / (THUMBNAIL_WIDTH + THUMBNAIL_GAP)))
}

let resizeObserver: ResizeObserver | null = null

onMounted(() => {
    if (!thumbnailRow.value) return
    resizeObserver = new ResizeObserver(updateVisibleThumbnails)
    resizeObserver.observe(thumbnailRow.value)
    updateVisibleThumbnails()
})

onUnmounted(() => resizeObserver?.disconnect())

const openAt = (index: number) => {
    if (!hasGallery.value) return
    startIndex.value = index
    lightboxOpen.value = true
}
</script>
