<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('impressum_page.title')" />

    <div>
        <div class="py-6">
            <div class="text-lg leading-relaxed text-gray-700 font-medium">
                <img
                    src="/images/impressum.jpg"
                    alt="impressum"
                    class="w-full lg:w-1/2 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5" />
                <div class="mb-5 pt-4">
                    <span>
                        {{ t('impressum_page.text1') }}
                    </span>
                </div>
                <div class="mb-5">
                    <span>
                        {{ t('impressum_page.director') }}
                        <br />
                        {{ t('impressum_page.chairman') }}
                    </span>
                </div>
                <div class="mb-5">
                    <span>{{ t('impressum_page.editor_in_chief') }}</span>
                </div>
                <div class="mb-5">
                    <span>{{ t('impressum_page.web_editor') }}</span>
                </div>
                <div class="mb-5">
                    <a
                        class="hover:cursor-pointer hover:underline"
                        href="mailto:standardclassicradio@advokati-stevanovic.com">
                        {{ t('impressum_page.email') }}
                    </a>
                </div>
                <div class="mb-5">
                    <img src="/images/standardprva-logo.png" alt="standardprva logo" class="h-12 w-auto" />
                    <a class="hover:cursor-pointer hover:underline" href="https://standardprva.ba" target="_blank">
                        www.standardprva.ba
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
