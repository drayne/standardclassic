<template>
    <div class="mt-6">
        <div
            class="mb-6 flex h-auto w-full items-center justify-center overflow-hidden md:float-left md:mr-8 md:mb-4 md:ml-0 md:w-58">
            <img
                src="/images/vijesti-sa-dankom.jpg"
                :alt="t('danka_podcast.title')"
                class="h-auto w-full rounded-lg object-contain shadow-sm" />
        </div>
        <div class="flex flex-col overflow-hidden md:overflow-visible">
            <h3 class="mb-1 text-xl font-bold">{{ t('danka_podcast.title') }}</h3>
            <p class="text-radio-red mb-3 text-sm font-bold italic">{{ t('danka_podcast.author') }}</p>
            <p class="mb-3 leading-relaxed text-gray-700">
                {{ t('danka_podcast.short_p') }}
            </p>
            <div class="mt-0">
                <button
                    class="text-radio-red text-base font-bold hover:underline hover:cursor-pointer transition duration-300"
                    @click="$inertia.visit('/vijesti-sa-dankom')">
                    <span v-html="t('podcast_components.aljosa.read_more')"></span>
                </button>
            </div>
        </div>
        <div class="clear-both"></div>
    </div>
</template>

<script setup lang="ts">
import { useTrans } from '@/Composables/useTrans'

const { t } = useTrans()
</script>
