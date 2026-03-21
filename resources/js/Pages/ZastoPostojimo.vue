<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('why_we_exist_page.title')" />

    <div>
        <div class="py-12">
            <div class="text-lg leading-relaxed text-gray-700">
                <img
                    src="/images/zasto-postojimo.png"
                    alt="Zasto postojimo"
                    class="w-full lg:w-1/3 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5" />
                <h1 class="text-2xl font-medium text-black mb-6" v-html="t('why_we_exist_page.h1')"></h1>
                <p class="mb-5">
                    {{ t('why_we_exist_page.p1') }}
                </p>

                <p class="mb-5">
                    {{ t('why_we_exist_page.p2') }}
                </p>

                <p class="mb-5">
                    {{ t('why_we_exist_page.p3') }}
                </p>

                <div class="mb-5">
                    <p>{{ t('why_we_exist_page.p4') }}</p>
                    <ul class="list-none pl-2">
                        <li>– {{ t('why_we_exist_page.li1') }}</li>
                        <li>– {{ t('why_we_exist_page.li2') }}</li>
                        <li>– {{ t('why_we_exist_page.li3') }}</li>
                        <li>– {{ t('why_we_exist_page.li4') }}</li>
                    </ul>
                </div>

                <p class="mb-5">
                    {{ t('why_we_exist_page.p5') }}
                </p>

                <p class="mb-5" v-html="t('why_we_exist_page.p6')"></p>

                <p class="mb-5" v-html="t('why_we_exist_page.p7')"></p>
            </div>
        </div>
    </div>
</template>
