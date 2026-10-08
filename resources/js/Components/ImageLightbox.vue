<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-200"
            enter-from-class="opacity-0"
            leave-active-class="transition-opacity duration-200"
            leave-to-class="opacity-0">
            <div
                v-if="open"
                class="fixed inset-0 z-[100] flex flex-col bg-black/80 backdrop-blur-sm select-none"
                role="dialog"
                aria-modal="true"
                @click.self="close"
                @touchstart.passive="onTouchStart"
                @touchend.passive="onTouchEnd">
                <div class="flex items-center justify-between px-4 py-3 text-sm text-white/80">
                    <span>{{ current + 1 }} / {{ images.length }}</span>
                    <button
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-full hover:bg-white/10 hover:cursor-pointer"
                        :aria-label="t('gallery.close')"
                        @click="close">
                        <i class="pi pi-times text-xl" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="relative flex min-h-0 flex-1 items-center justify-center px-2 sm:px-16" @click.self="close">
                    <img
                        :key="images[current]"
                        :src="images[current]"
                        :alt="alt"
                        class="max-h-full max-w-full object-contain" />

                    <button
                        v-if="images.length > 1"
                        type="button"
                        class="absolute left-2 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/25 hover:cursor-pointer"
                        :aria-label="t('gallery.previous')"
                        @click="prev">
                        <i class="pi pi-chevron-left text-xl" aria-hidden="true"></i>
                    </button>
                    <button
                        v-if="images.length > 1"
                        type="button"
                        class="absolute right-2 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/25 hover:cursor-pointer"
                        :aria-label="t('gallery.next')"
                        @click="next">
                        <i class="pi pi-chevron-right text-xl" aria-hidden="true"></i>
                    </button>
                </div>

                <div v-if="caption" class="px-4 pt-2 text-center text-xs font-bold text-white/60 italic">
                    {{ caption }}
                </div>

                <div v-if="images.length > 1" class="flex justify-center gap-2 overflow-x-auto px-4 py-3">
                    <button
                        v-for="(image, index) in images"
                        :key="image"
                        type="button"
                        class="h-14 w-20 shrink-0 overflow-hidden rounded-sm border-2 hover:cursor-pointer"
                        :class="index === current ? 'border-white' : 'border-transparent opacity-50 hover:opacity-100'"
                        @click="current = index">
                        <img :src="image" alt="" class="h-full w-full object-cover" />
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup lang="ts">
import { useTrans } from '@/Composables/useTrans'
import { onUnmounted, ref, watch } from 'vue'

const { t } = useTrans()

const props = withDefaults(
    defineProps<{
        images: string[]
        open: boolean
        startIndex?: number
        alt?: string
        caption?: string | null
    }>(),
    { startIndex: 0, alt: '', caption: null },
)

const emit = defineEmits<{ close: [] }>()

const current = ref(props.startIndex)
let touchStartX: number | null = null

const close = () => emit('close')

const prev = () => {
    current.value = (current.value - 1 + props.images.length) % props.images.length
}

const next = () => {
    current.value = (current.value + 1) % props.images.length
}

const onKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') close()
    if (event.key === 'ArrowLeft') prev()
    if (event.key === 'ArrowRight') next()
}

const onTouchStart = (event: TouchEvent) => {
    touchStartX = event.changedTouches[0].clientX
}

const onTouchEnd = (event: TouchEvent) => {
    if (touchStartX === null) return
    const deltaX = event.changedTouches[0].clientX - touchStartX
    touchStartX = null
    if (Math.abs(deltaX) < 50) return
    if (deltaX > 0) prev()
    else next()
}

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            current.value = props.startIndex
            document.addEventListener('keydown', onKeydown)
            document.body.style.overflow = 'hidden'
        } else {
            document.removeEventListener('keydown', onKeydown)
            document.body.style.overflow = ''
        }
    },
)

onUnmounted(() => {
    document.removeEventListener('keydown', onKeydown)
    document.body.style.overflow = ''
})
</script>
