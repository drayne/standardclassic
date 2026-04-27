<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
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
        </div>
    </div>
</template>
