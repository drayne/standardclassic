<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('podcasts_page.title')" />

    <div>
        <div class="py-6">
            <div class="text-lg leading-relaxed text-gray-700">
                <img
                    src="/images/podkasti-na-standardclassic-radiju.jpg"
                    alt="podkasti"
                    class="w-full lg:w-1/2 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5" />
                <h1 class="text-2xl font-medium text-black mb-6 pt-4" v-html="t('podcasts_page.h1')"></h1>
                <p class="mb-5" v-html="t('podcasts_page.p1')"></p>
            </div>

            <div class="mt-10 lg:flex items-start w-full p-6 rounded-lg">
                <div class="lg:w-1/3 mb-6 lg:mb-0 lg:mr-10 pt-2">
                    <img src="/images/aljosa-logo-crop.jpg" alt="aljosa-logo" class="w-full h-auto rounded shadow-sm" />
                </div>
                <div class="lg:w-2/3 text-lg leading-relaxed text-gray-700">
                    <h2 class="text-xl font-medium text-black mb-4" v-html="t('podcasts_page.aljosa_title')"></h2>
                    <p class="mb-4" v-html="t('podcasts_page.aljosa_p1')"></p>
                    <p class="mb-4" v-html="t('podcasts_page.aljosa_p2')"></p>
                    <p class="mb-4" v-html="t('podcasts_page.aljosa_p3')"></p>
                    <p class="mb-4" v-html="t('podcasts_page.aljosa_p4')"></p>
                </div>
            </div>

            <div class="mt-12 lg:flex items-start w-full p-6 rounded-lg">
                <div class="lg:w-1/3 mb-6 lg:mb-0 lg:mr-10">
                    <img
                        src="/images/milos-stevanovic-podcast.jpg"
                        alt="milos-stevanovic-podcast"
                        class="w-full h-auto rounded" />
                </div>
                <div class="lg:w-2/3 text-lg leading-relaxed text-gray-700">
                    <h2 class="text-xl font-medium text-black mb-4" v-html="t('podcasts_page.milos_title')"></h2>
                    <p class="mb-4" v-html="t('podcasts_page.milos_p1')"></p>
                    <p class="mb-4" v-html="t('podcasts_page.milos_p2')"></p>
                    <p class="mb-4" v-html="t('podcasts_page.milos_p3')"></p>
                </div>
            </div>
        </div>
    </div>
</template>
