<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

const sideImages = [
    { src: '/images/filmska/4.jpg', altKey: 'film_music_page.image_4_alt' },
    { src: '/images/filmska/5.jpg', altKey: 'film_music_page.image_5_alt' },
    { src: '/images/filmska/6.jpg', altKey: 'film_music_page.image_6_alt' },
]

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head :title="t('film_music_page.title')" />

    <div class="overflow-hidden bg-white text-slate-800">
        <section class="relative -mx-1 aspect-[2612/820] overflow-hidden bg-slate-950 sm:mx-0 sm:aspect-[2612/670]">
            <img
                src="/images/filmska/1sm.jpg"
                :alt="t('film_music_page.hero_image_alt')"
                class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute inset-0 bg-slate-950/50 sm:bg-transparent"></div>
            <div class="absolute left-[8%] top-[17%] w-[53%] text-white sm:left-[18.5%] sm:top-[16%] sm:w-[52%]">
                <h1
                    class="font-normal font-serif text-[clamp(1.15rem,7vw,1.2rem)] leading-[0.82] sm:text-[clamp(1.1rem,3.3vw,3.25rem)]"
                    v-html="t('film_music_page.hero_title')"></h1>
                <p
                    class="mt-[3%] text-[clamp(0.55rem,2.5vw,0.4rem)] font-normal uppercase tracking-[0.12em] sm:mt-[4%] sm:text-[clamp(0.4rem,1vw,0.95rem)] sm:tracking-[0.18em]">
                    {{ t('film_music_page.hero_schedule') }}
                </p>
                <div class="mt-[2%] h-px w-[26%] bg-red-500 sm:mt-[3%]"></div>
                <p
                    class="mt-[2%] font-normal font-serif text-[clamp(0.3rem,3.2vw,1.2rem)] italic sm:mt-[3%] sm:text-[clamp(0.65rem,1.3vw,1.25rem)]">
                    {{ t('film_music_page.hero_subtitle') }}
                </p>
            </div>
            <div class="absolute left-[64%] top-[16%] w-[32%] text-white sm:left-[78%] sm:top-[18%] sm:w-[21%]">
                <p
                    class="font-normal font-serif text-[clamp(0.62rem,2.7vw,0.95rem)] italic leading-[1.25] sm:text-[clamp(0.5rem,1vw,1rem)] sm:leading-[1.35]"
                    v-html="t('film_music_page.hero_right_text')"></p>
                <div class="mt-[8%] h-px w-[22%] bg-red-500 sm:mt-[6%]"></div>
            </div>
        </section>

        <section
            class="grid gap-10 px-0 py-12 sm:px-10 lg:grid-cols-[1.35fr_0.9fr_0.72fr] lg:gap-8 lg:px-0 lg:pt-10 lg:pb-4">
            <article class="text-base leading-relaxed text-slate-600 sm:text-lg">
                <h2 class="mb-6 font-serif text-2xl leading-tight text-slate-900 sm:text-4xl">{{
                    t('film_music_page.intro_title')
                }}</h2>
                <p class="mb-5">{{ t('film_music_page.intro_p1') }}</p>
                <p class="mb-5">{{ t('film_music_page.intro_p2') }}</p>
                <p>{{ t('film_music_page.intro_p3') }}</p>
            </article>

            <article class="border-t border-slate-200 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:py-4">
                <img
                    src="/images/filmska/3.jpg"
                    :alt="t('film_music_page.image_3_alt')"
                    class="mb-7 aspect-[4/3] w-full object-cover" />
                <h2 class="font-serif mt-6 text-3xl leading-tight text-slate-900">{{
                    t('film_music_page.composers_title')
                }}</h2>
                <p class="mt-6 text-base leading-relaxed text-slate-600">{{ t('film_music_page.composers_text') }}</p>
            </article>

            <aside class="space-y-3 lg:py-4">
                <div class="bg-stone-100 p-6 text-slate-700">
                    <div class="mb-5 flex items-start gap-4">
                        <img
                            src="/images/filmska/kalendar.png"
                            :alt="t('film_music_page.schedule_icon_alt')"
                            class="h-10 w-10 object-contain" />
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-red-600">{{
                                t('film_music_page.schedule_label')
                            }}</p>
                            <p class="mt-2 font-serif text-xl italic" v-html="t('film_music_page.schedule')"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 border-t border-stone-200 pt-5">
                        <img
                            src="/images/filmska/slusalice.png"
                            :alt="t('film_music_page.listen_icon_alt')"
                            class="h-10 w-10 object-contain" />
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-red-600">{{
                                t('film_music_page.listen_label')
                            }}</p>
                            <ul class="mt-2 space-y-2 text-sm leading-relaxed">
                                <li class="flex items-start gap-2">
                                    <i class="pi pi-play mt-1 text-base text-slate-700" aria-hidden="true"></i>
                                    <span>{{ t('film_music_page.listen_web') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="pi pi-mobile mt-1 text-base text-slate-700" aria-hidden="true"></i>
                                    <span>{{ t('film_music_page.listen_mobile') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="pi pi-volume-up mt-1 text-base text-slate-700" aria-hidden="true"></i>
                                    <span>{{ t('film_music_page.listen_devices') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <img
                    v-for="image in sideImages"
                    :key="image.src"
                    :src="image.src"
                    :alt="t(image.altKey)"
                    class="block aspect-[2.5/1] w-full object-cover" />
            </aside>
        </section>

        <blockquote
            class="my-6 w-full border-l-4 border-red-500 pl-6 font-serif text-xl italic leading-relaxed text-slate-800 sm:text-2xl">
            <span v-html="t('film_music_page.quote')"></span>
            <footer class="mt-4 text-[10px] font-sans font-semibold uppercase tracking-[0.3em] text-slate-400">
                StandardClassic Radio
            </footer>
        </blockquote>

        <section
            class="relative -mx-4 aspect-[3124/650] overflow-hidden bg-stone-100 text-left sm:mx-0 sm:aspect-[3124/350]">
            <img
                src="/images/filmska/2sm.jpg"
                :alt="t('film_music_page.footer_image_alt')"
                class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute inset-0 bg-stone-100/20"></div>
            <div class="absolute left-[14%] top-[20%] w-[82%] text-left sm:left-[30%] sm:top-[24%]">
                <p
                    class="font-normal font-serif text-[clamp(0.8rem,3.5vw,1.35rem)] italic leading-[1.2] text-slate-800 sm:text-[clamp(0.5rem,1.35vw,1.3rem)]">
                    <span class="block">{{ t('film_music_page.footer_quote_line1') }}</span>
                    <span class="ml-0 block sm:ml-[6%]">{{ t('film_music_page.footer_quote_line2') }}</span>
                </p>
            </div>
            <div class="absolute left-[68%] top-[70%] h-px w-[5.5%] bg-red-500"></div>
            <p
                class="absolute left-[74%] top-[73%] text-[clamp(0.5rem,1.8vw,0.75rem)] font-semibold uppercase tracking-[0.18em] text-slate-500 sm:left-[84%] sm:text-[clamp(0.35rem,0.65vw,0.65rem)] sm:tracking-[0.3em]">
                Hans Zimmer
            </p>
        </section>
    </div>
</template>
