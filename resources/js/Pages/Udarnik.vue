<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('udarnik_page.title')" />

    <div class="udarnik-page overflow-hidden bg-white text-slate-800">
        <img
            src="/images/udarnik.jpg"
            :alt="t('udarnik_page.hero_image_alt')"
            class="block h-auto w-full" />

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1fr)_280px] lg:items-start">
            <article>
                <h1 class="font-normal font-serif text-3xl leading-tight text-slate-900 sm:text-4xl lg:text-[2.65rem]">
                    {{ t('udarnik_page.h1') }}
                </h1>

                <div class="mt-4 flex items-center gap-2 text-sm text-slate-500">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.75"
                        class="h-4 w-4"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.75 3.75v2.5m10.5-2.5v2.5M4.5 9.25h15" />
                        <rect width="15" height="15" x="4.5" y="5.25" rx="1.5" />
                    </svg>
                    <time datetime="2026-09-25">{{ t('udarnik_page.date') }}</time>
                </div>

                <div class="mt-4 mb-5 h-px w-full bg-[#b17d2d]"></div>

                <div class="space-y-5 text-base leading-relaxed text-slate-600 sm:text-lg">
                    <p>
                        {{ t('udarnik_page.p1') }}
                    </p>
                    <p>
                        {{ t('udarnik_page.p2') }}
                    </p>
                    <p>
                        {{ t('udarnik_page.p3') }}
                    </p>
                    <p>
                        {{ t('udarnik_page.p4') }}
                    </p>
                    <p>
                        {{ t('udarnik_page.p5') }}
                    </p>
                    <p>{{ t('udarnik_page.p6') }}</p>
                </div>
            </article>

            <aside class="overflow-hidden rounded-md bg-[#faf2e1] p-3 sm:p-4">
                <div class="px-4 pt-4 pb-5 sm:px-5 sm:pt-5">
                    <h2 class="font-normal font-serif text-3xl leading-[1.08] text-slate-900 sm:text-4xl">
                        {{ t('udarnik_page.side_title') }}
                    </h2>
                    <div class="mt-5 h-0.5 w-12 bg-[#b17d2d]"></div>
                    <p class="mt-4 font-normal font-serif text-2xl leading-tight text-slate-900">
                        {{ t('udarnik_page.side_subtitle') }}
                    </p>
                    <a
                        href="mailto:udarnik@advokati-stevanovic.com"
                        class="mt-5 flex items-center justify-center gap-3 rounded-full bg-[#be211f] px-5 py-3 font-normal font-serif text-lg text-white transition hover:bg-[#a51c1a]">
                        {{ t('udarnik_page.apply') }}
                        <span class="text-2xl leading-none" aria-hidden="true">→</span>
                    </a>
                </div>

                <img :src="'/images/udarnik-side.jpg'" :alt="t('udarnik_page.side_image_alt')" class="block h-auto w-full" />
            </aside>
        </div>

        <section
            class="mt-4 flex flex-col items-center justify-center gap-5 rounded-md bg-[#faf2e1] px-6 py-5 text-center sm:flex-row sm:gap-6 sm:px-10">
            <div class="flex shrink-0 items-center justify-center text-[#b17d2d] sm:w-16">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    class="h-12 w-12"
                    aria-hidden="true">
                    <rect width="19" height="14" x="2.5" y="5" rx="1" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 6 9 7 9-7" />
                </svg>
            </div>

            <div class="hidden h-14 w-px bg-[#c6a36b] sm:block"></div>

            <div class="font-normal font-serif text-lg leading-relaxed text-slate-900 sm:text-xl">
                <p>
                    {{ t('udarnik_page.email_label') }}
                    <a href="mailto:udarnik@advokati-stevanovic.com" class="font-bold text-[#be211f] hover:underline">
                        udarnik@advokati-stevanovic.com
                    </a>
                </p>
                <div class="my-2 h-px w-full bg-[#c6a36b]"></div>
                <p><span class="font-bold">UDARNIK</span> - {{ t('udarnik_page.contact_text') }}</p>
            </div>
        </section>
    </div>
</template>
