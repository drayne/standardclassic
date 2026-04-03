<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('shows_page.title')" />

    <div>
        <div class="py-12">
            <div class="text-lg leading-relaxed text-gray-700">
                <img
                    src="/images/emisije-na-nasem-radiju.jpg"
                    alt="Zasto postojimo"
                    class="w-full lg:w-1/3 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5" />
                <h1 class="text-2xl font-medium text-black mb-6">{{ t('shows_page.h1') }}</h1>
                <p class="mb-5" v-html="t('shows_page.p1')"></p>

                <p class="mb-5">
                    {{ t('shows_page.p2') }}
                </p>

                <p class="mb-5">
                    {{ t('shows_page.p3') }}
                </p>

                <p class="mb-5" v-html="t('shows_page.p4')"></p>

                <p class="mb-5" v-html="t('shows_page.p5')"></p>

                <div
                    class="mt-12 border-t border-gray-300 pt-8 flex flex-col gap-8 lg:gap-64 lg:flex-row lg:items-center lg:align-middle">
                    <div class="flex flex-col gap-2">
                        <span class="font-bold">{{ t('shows_page.aca_show_name') }}</span>
                        <Link :href="route('popodne-sa-Acom-Informacijom')" class="hover:underline">
                            <span class="font-bold underline">{{ t('shows_page.archive_link') }}</span>
                        </Link>
                    </div>
                    <img
                        src="/images/popodne-sa-acom-informacijom.jpg"
                        :alt="t('aca_podcast.title')"
                        class="h-auto w-full lg:h-72 lg:w-auto" />
                </div>
            </div>
        </div>
    </div>
</template>
