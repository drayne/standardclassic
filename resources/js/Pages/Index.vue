<template>
    <Head title="Početna" />

    <div>
        <!-- Hero Section -->
        <section class="mb-16 grid grid-cols-1 items-stretch gap-8 lg:grid-cols-2">
            <div class="flex items-center justify-center">
                <img src="/images/klasicna-muzika.jpg" alt="Klasična muzika" class="h-auto w-full" />
            </div>
            <div class="flex">
                <img
                    src="/images/slika-andjeli.jpg"
                    alt="Umetnička ilustracija - Anđeli"
                    class="w-full rounded-lg object-cover shadow-md" />
            </div>
        </section>

        <!-- Welcome Section -->
        <section class="mb-16">
            <h2 class="mb-2 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                O nama
            </h2>
            <div class="mt-4 grid grid-cols-1 gap-12 lg:grid-cols-2">
                <div class="lg:col-span-1">
                    <div class="mb-4 flex flex-col overflow-hidden">
                        <img
                            src="/images/gustavo-dudamel.jpg"
                            alt="Gustavo Dudamel"
                            class="h-64 w-full rounded-lg object-cover shadow-md" />
                        <span class="mt-2 text-sm text-gray-500 italic">
                            Gustavo Dudamel - direktor Njujorške filharmonije
                        </span>
                    </div>
                </div>
                <div class="prose max-w-none lg:col-span-1">
                    <h1 class="mb-7 inline-block text-2xl">
                        Dobro došli na
                        <b>Standard</b>
                        <span class="text-red-700">Classic</span>
                        Radio
                    </h1>
                    <p class="mb-7 text-lg leading-relaxed text-gray-700">
                        Naš cilj je da vratimo kulturu na velika vrata u regionu, ne samo kroz klasičnu muziku, nego i
                        kroz priču o filmovima, stripu, starim gramofonskim pločama, slikarstvu, pozorištu, ali i da
                        pratimo svakodnevna dešavanja u redovnom životu.
                    </p>
                    <p class="text-radio-red cursor-pointer text-lg font-bold hover:underline">
                        Pročitajte više o nama i našim planovima &rarr;
                    </p>
                </div>
            </div>
        </section>

        <!-- Culture News Section -->
        <section class="mb-16">
            <h2 class="mb-3 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                Vijesti iz kulture
            </h2>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div
                    v-for="article in kulturaArticles"
                    :key="article.id"
                    class="flex overflow-hidden rounded-md border border-gray-200 bg-white transition hover:shadow-md hover:cursor-pointer"
                    @click="$inertia.visit(`/vijest/${article.slug}`)">
                    <div class="h-64 w-2/5 flex-shrink-0">
                        <img
                            v-if="article.image"
                            :src="article.image"
                            :alt="article.title || ''"
                            class="h-full w-full object-cover" />
                        <div v-else class="flex h-full items-center justify-center">
                            <p class="text-xs text-gray-400 italic">[Nema slike]</p>
                        </div>
                    </div>
                    <div class="flex w-full flex-col justify-between p-6">
                        <div>
                            <h3 class="mb-2 line-clamp-2 text-lg font-bold">
                                {{ article.title }}
                            </h3>
                            <div class="mb-4 line-clamp-2 text-sm text-gray-600" v-html="article.content"></div>
                        </div>
                        <button class="text-radio-red text-left text-sm font-bold hover:cursor-pointer">
                            Pročitaj više &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom Grid -->
        <section class="grid grid-cols-1 gap-16 pb-12 lg:grid-cols-2">
            <!-- Daily News -->
            <div>
                <h2 class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    Vijesti iz dnevno-političkog života
                </h2>
                <ul class="mt-6 space-y-4">
                    <li
                        v-for="article in dpArticles"
                        :key="article.id"
                        class="flex items-center gap-6 border-b border-gray-300 pb-4 last:border-0">
                        <span class="text-radio-red block text-sm font-bold">{{ article.published_at }}</span>
                        <a href="#" class="hover:text-radio-red text-base font-medium transition">
                            {{ article.title }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Podcasts & Shows -->
            <div>
                <h2 class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    Podkasti i emisije
                </h2>
                <div class="mt-6">
                    <div
                        class="mb-6 flex h-auto w-full items-center justify-center overflow-hidden md:float-left md:mr-8 md:mb-4 md:ml-0 md:w-58">
                        <img
                            src="/images/aljosa-logo.jpg"
                            alt="Aljoša Ljubojević"
                            class="h-auto w-full rounded-lg object-contain shadow-sm" />
                    </div>
                    <div class="flex flex-col overflow-hidden md:overflow-visible">
                        <h3 class="mb-1 text-xl font-bold">Gdje se fura (ne)kultura</h3>
                        <p class="text-radio-red mb-3 text-sm font-bold italic">Autor: Aljoša Ljubojević</p>
                        <p class="mb-3 leading-relaxed text-gray-700">
                            Gdje se fura (ne)kultura je naša najbolja emisija gdje slušaoci i gledaoci mogu da prate
                            odličan podkast serija koji vodi Aljoša Ljubojević, poznati novinar iz Bijeljine. Aljoša će
                            razgovarati sa brojnim kulturnim radnicima, ali i poznavaocima filma, sporta, dobre muzike,
                            stripova i mnogo toga.
                        </p>
                        <div class="mt-0">
                            <button
                                class="text-radio-red text-base font-bold hover:underline hover:cursor-pointer transition duration-300"
                                @click="$inertia.visit('/gdje-se-fura-nekultura')">
                                Pogledajte detaljnije &rarr;
                            </button>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { Article } from '@/types'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })

defineProps<{
    kulturaArticles: Article[]
    dpArticles: Article[]
}>()

const dailyNews = [
    {
        date: '13.03.2026',
        title: 'Sastanak Standard Classic Radio radne grupe',
    },
    { date: '12.03.2026', title: 'Kulturna dešavanja u Bijeljini' },
    { date: '11.03.2026', title: 'Izveštaj o privrednom rastu u regionu' },
    { date: '10.03.2026', title: 'Novi zakon o saobraćaju stupa na snagu' },
    { date: '09.03.2026', title: 'Otvoren novi tehnološki park' },
    { date: '08.03.2026', title: 'Međunarodni dan žena obeležen širom zemlje' },
]
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
