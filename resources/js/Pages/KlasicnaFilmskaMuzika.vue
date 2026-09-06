<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('film_music_page.title')" />

    <div class="overflow-hidden bg-white text-slate-800">
        <section class="relative aspect-[2612/670] overflow-hidden bg-slate-950">
            <img
                src="/images/filmska/1sm.jpg"
                :alt="t('film_music_page.hero_image_alt')"
                class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute left-[18.5%] top-[16%] w-[52%] text-white">
                <h1
                    class="font-normal font-serif text-[clamp(1.1rem,3.3vw,3.25rem)] leading-[0.92]"
                    v-html="t('film_music_page.hero_title')"></h1>
                <p class="mt-[4%] text-[clamp(0.4rem,1vw,0.95rem)] font-normal uppercase tracking-[0.18em]">
                    {{ t('film_music_page.hero_schedule') }}
                </p>
                <div class="mt-[3%] h-px w-[26%] bg-red-500"></div>
                <p class="mt-[3%] font-normal font-serif text-[clamp(0.65rem,1.3vw,1.25rem)] italic">
                    {{ t('film_music_page.hero_subtitle') }}
                </p>
            </div>
            <div class="absolute left-[78%] top-[18%] w-[21%] text-white">
                <p
                    class="font-normal font-serif text-[clamp(0.5rem,1vw,1rem)] italic leading-[1.35]"
                    v-html="t('film_music_page.hero_right_text')"></p>
                <div class="mt-[6%] h-px w-[22%] bg-red-500"></div>
            </div>
        </section>

        <section class="grid gap-10 px-6 py-12 sm:px-10 lg:grid-cols-[1.35fr_0.9fr_0.72fr] lg:gap-8 lg:px-0 lg:py-10">
            <article class="text-base leading-relaxed text-slate-600 sm:text-lg">
                <h2 class="mb-6 font-serif text-3xl leading-tight text-slate-900 sm:text-4xl">{{
                    t('film_music_page.intro_title')
                }}</h2>
                <p class="mb-5">{{ t('film_music_page.intro_p1') }}</p>
                <p class="mb-5">{{ t('film_music_page.intro_p2') }}</p>
                <p>{{ t('film_music_page.intro_p3') }}</p>
                <blockquote
                    class="mt-10 border-l-4 border-red-500 pl-6 font-serif text-xl italic leading-relaxed text-slate-800 sm:text-2xl">
                    “{{ t('film_music_page.quote') }}”
                    <footer class="mt-4 text-[10px] font-sans font-semibold uppercase tracking-[0.3em] text-slate-400"
                        >StandardClassic Radio</footer
                    >
                </blockquote>
            </article>

            <article class="border-t border-slate-200 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                <div
                    class="mb-7 flex aspect-[4/3] items-center justify-center bg-slate-800 p-6 text-center text-xs uppercase tracking-[0.2em] text-slate-300">
                    {{ t('film_music_page.placeholder') }}
                </div>
                <h2 class="font-serif text-3xl leading-tight text-slate-900">{{
                    t('film_music_page.composers_title')
                }}</h2>
                <p class="mt-4 text-base leading-relaxed text-slate-600">{{ t('film_music_page.composers_text') }}</p>
            </article>

            <aside class="space-y-3">
                <div class="bg-stone-100 p-6 text-slate-700">
                    <div class="mb-5 flex items-start gap-4">
                        <span class="text-3xl text-red-600" aria-hidden="true">▣</span>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-red-600">{{
                                t('film_music_page.schedule_label')
                            }}</p>
                            <p class="mt-2 font-serif text-xl italic" v-html="t('film_music_page.schedule')"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 border-t border-stone-200 pt-5">
                        <span class="text-3xl text-red-600" aria-hidden="true">◉</span>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-red-600">{{
                                t('film_music_page.listen_label')
                            }}</p>
                            <ul class="mt-2 space-y-2 text-sm leading-relaxed">
                                <li>▸ {{ t('film_music_page.listen_web') }}</li>
                                <li>▯ {{ t('film_music_page.listen_mobile') }}</li>
                                <li>◖ {{ t('film_music_page.listen_devices') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div
                    v-for="slot in 3"
                    :key="slot"
                    class="flex aspect-[2.5/1] items-center justify-center bg-slate-700 px-4 text-center text-[10px] uppercase tracking-[0.2em] text-slate-300">
                    {{ t('film_music_page.placeholder') }} {{ slot }}
                </div>
            </aside>
        </section>

        <section class="relative aspect-[3124/350] overflow-hidden bg-stone-100 text-left">
            <img
                src="/images/filmska/2sm.jpg"
                :alt="t('film_music_page.footer_image_alt')"
                class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute inset-0 bg-stone-100/20"></div>
            <div class="absolute left-[30%] top-[24%] w-[72%] text-left">
                <p
                    class="font-normal font-serif text-[clamp(0.5rem,1.35vw,1.3rem)] italic leading-[1.2] text-slate-800">
                    <span class="block">{{ t('film_music_page.footer_quote_line1') }}</span>
                    <span class="ml-[6%] block">{{ t('film_music_page.footer_quote_line2') }}</span>
                </p>
            </div>
            <div class="absolute left-[68%] top-[70%] h-px w-[5.5%] bg-red-500"></div>
            <p
                class="absolute left-[84%] top-[72%] text-[clamp(0.35rem,0.65vw,0.65rem)] font-semibold uppercase tracking-[0.3em] text-slate-500">
                Hans Zimmer
            </p>
        </section>
    </div>
</template>
