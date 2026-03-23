<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('program_page.title')" />

    <div>
        <div class="py-12">
            <div class="text-lg leading-relaxed text-gray-700">
                <img
                    src="/images/program-radija.jpg"
                    alt="Program radija"
                    class="w-full lg:w-1/2 h-auto float-none lg:float-left lg:mr-14 mb-10 lg:mb-5" />
                <h1 class="text-2xl text-black mb-6" v-html="t('program_page.h1')"></h1>
                <p class="mb-5">
                    {{ t('program_page.p1') }}
                </p>

                <p class="mb-5" v-html="t('program_page.p2')"></p>

                <p class="mb-5">
                    {{ t('program_page.p3') }}
                </p>

                <p class="mb-5" v-html="t('program_page.p4')"></p>

                <p class="mb-5">
                    {{ t('program_page.p5') }}
                </p>

                <p class="mb-5" v-html="t('program_page.p6')"></p>
                <p class="mb-5" v-html="t('program_page.p7')"></p>
            </div>
        </div>
    </div>
</template>
