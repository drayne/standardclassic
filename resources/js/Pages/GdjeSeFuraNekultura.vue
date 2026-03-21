<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('culture_podcast.title')" />

    <div>
        <div class="py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <img src="/images/aljosa-logo-crop.jpg" alt="Aljosa logo" class="w-auto h-auto aspect-auto mt-2" />

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <h1 class="inline-block text-2xl font-medium">{{ t('culture_podcast.welcome_h1') }}</h1>
                        <p class="text-radio-red mb-3 text-base font-bold italic">
                            {{ t('culture_podcast.author') }}
                        </p>
                    </div>
                    <p class="-mt-2 text-lg leading-relaxed text-gray-700">
                        {{ t('culture_podcast.p1') }}
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        {{ t('culture_podcast.p2') }}
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        {{ t('culture_podcast.p3') }}
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        {{ t('culture_podcast.p4') }}
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        {{ t('culture_podcast.p5') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
