<template>
    <Head title="Početna" />

    <MainLayout>
        <!-- Hero Section -->
        <section
            class="mb-20 grid grid-cols-1 items-stretch gap-8 lg:grid-cols-2"
        >
            <div class="flex items-center justify-center">
                <img
                    src="/images/klasicna-muzika.jpg"
                    alt="Klasična muzika"
                    class="h-auto w-full"
                />
            </div>
            <div class="flex">
                <img
                    src="/images/slika-andjeli.jpg"
                    alt="Umetnička ilustracija - Anđeli"
                    class="w-full rounded-lg object-cover shadow-md"
                />
            </div>
        </section>

        <!-- Welcome Section -->
        <section class="-mb-44">
            <h2
                class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase"
            >
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                O nama
            </h2>
            <hr v-if="false" class="mb-6 text-gray-300" />
            <div class="mt-4 grid grid-cols-1 gap-12 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <div
                        class="mb-4 flex aspect-square flex-col overflow-hidden"
                    >
                        <img
                            src="/images/gustavo-dudamel.jpg"
                            alt="Gustavo Dudamel"
                            class="h-64 w-full rounded-lg object-cover shadow-md"
                        />
                        <span class="mt-2 text-sm text-gray-500 italic"
                            >Gustavo Dudamel - direktor Njujorške
                            filharmonije</span
                        >
                    </div>
                </div>
                <div class="prose max-w-none lg:col-span-2">
                    <h1 class="mb-7 inline-block text-2xl">
                        Dobro došli na <b>Standard</b
                        ><span class="text-red-700">Classic</span> Radio
                    </h1>
                    <p class="mb-7 text-lg leading-relaxed text-gray-700">
                        Naš cilj je da vratimo kulturu na velika vrata u
                        regionu, ne samo kroz klasičnu muziku, nego i kroz priču
                        o filmovima, stripu, starim gramofonskim pločama,
                        slikarstvu, pozorištu, ali i da pratimo svakodnevna
                        dešavanja u redovnom životu.
                    </p>
                    <p
                        class="text-radio-red cursor-pointer text-lg font-bold hover:underline"
                    >
                        Pročitajte više o nama i našim planovima &rarr;
                    </p>
                </div>
            </div>
        </section>

        <!-- Culture News & Comic Section -->
        <section
            v-if="false"
            class="mb-20 grid grid-cols-1 gap-16 lg:grid-cols-2"
        >
            <!-- Culture News -->
            <div>
                <h2
                    class="mb-8 flex items-center text-2xl font-bold tracking-wider uppercase"
                >
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    Vijesti iz kulture
                </h2>
                <div
                    class="overflow-hidden rounded-xl border bg-white shadow-sm transition hover:shadow-md"
                >
                    <div
                        class="flex h-64 items-center justify-center bg-gray-200"
                    >
                        <p class="text-gray-400 italic">
                            [Slika: Violinistkinja]
                        </p>
                    </div>
                    <div class="p-6">
                        <h3 class="mb-3 text-xl font-bold">
                            Virtuoznost na sceni: Koncertni spektakl u Narodnom
                            pozorištu
                        </h3>
                        <p class="mb-4 text-gray-600">
                            Mlada zvezda svetske violine oduševila je publiku
                            svojim izvođenjem Čajkovskog...
                        </p>
                        <button
                            class="text-radio-red font-bold hover:underline"
                        >
                            Pročitaj više &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- Comic / Iznogud -->
            <div>
                <h2
                    class="mb-8 flex items-center text-2xl font-bold tracking-wider uppercase"
                >
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    Strip kutak
                </h2>
                <div class="rounded-xl bg-gray-100 p-8">
                    <div
                        class="mb-6 flex aspect-video items-center justify-center rounded-lg bg-gray-300"
                    >
                        <p class="text-gray-500 italic">[Strip: Iznogud]</p>
                    </div>
                    <h3 class="mb-4 text-xl font-bold uppercase">
                        Iznogud: Želja da se bude kalif umesto kalifa
                    </h3>
                    <p class="text-gray-700">
                        Ove nedelje pratimo nove spletke najpoznatijeg velikog
                        vezira u potrazi za moći...
                    </p>
                </div>
            </div>
        </section>

        <!-- Bottom Grid -->
        <section
            v-if="true"
            class="grid grid-cols-1 gap-16 pb-12 lg:grid-cols-2"
        >
            <!-- Daily News -->
            <div>
                <h2
                    class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase"
                >
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    Vijesti iz dnevno-političkog života
                </h2>
                <ul class="mt-6 space-y-4">
                    <li
                        v-for="article in kulturaArticles"
                        :key="article.id"
                        class="flex items-center gap-6 border-b border-gray-300 pb-4 last:border-0"
                    >
                        <span class="text-radio-red block text-sm font-bold">{{
                            article.published_at
                        }}</span>
                        <a
                            href="#"
                            class="hover:text-radio-red text-base font-medium transition"
                            >{{ article.title }}</a
                        >
                    </li>
                </ul>
            </div>

            <!-- Podcasts & Shows -->
            <div>
                <h2
                    class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase"
                >
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    Podkasti i emisije
                </h2>
                <div class="mt-6">
                    <div
                        class="mb-6 flex h-auto w-full items-center justify-center overflow-hidden md:float-left md:mr-8 md:mb-4 md:ml-0 md:w-58"
                    >
                        <img
                            src="/images/aljosa-logo.jpg"
                            alt="Aljoša Ljubojević"
                            class="h-auto w-full rounded-lg object-contain shadow-sm"
                        />
                    </div>
                    <div
                        class="flex flex-col overflow-hidden md:overflow-visible"
                    >
                        <h3 class="mb-1 text-xl font-bold">
                            Gdje se fura (ne)kultura
                        </h3>
                        <p class="text-radio-red mb-3 text-sm font-bold italic">
                            Autor: Aljoša Ljubojević
                        </p>
                        <p class="mb-3 leading-relaxed text-gray-700">
                            Gdje se fura (ne)kultura je naša najbolja emisija
                            gdje slušaoci i gledaoci mogu da prate odličan
                            podkast serija koji vodi Aljoša Ljubojević, poznati
                            novinar iz Bijeljine. Aljoša će razgovarati sa
                            brojnim kulturnim radnicima, ali i poznavaocima
                            filma, sporta, dobre muzike, stripova i mnogo toga.
                        </p>
                        <div class="mt-0">
                            <button
                                class="text-radio-red text-base font-bold hover:underline"
                            >
                                Pogledajte detaljnije &rarr;
                            </button>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { Article } from '@/types'
import { Head } from '@inertiajs/vue3'

defineProps<{
    kulturaArticles: Article[]
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
