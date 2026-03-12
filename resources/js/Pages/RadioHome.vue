<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

const dailyNews = [
    { date: '11.03.2026', title: 'Izveštaj o privrednom rastu u regionu' },
    { date: '10.03.2026', title: 'Novi zakon o saobraćaju stupa na snagu' },
    { date: '09.03.2026', title: 'Otvoren novi tehnološki park' },
    { date: '08.03.2026', title: 'Međunarodni dan žena obeležen širom zemlje' },
];
</script>

<template>
    <Head title="Početna" />

    <MainLayout>
        <!-- Hero Section -->
        <section class="mb-20 grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <div class="flex items-center justify-center">
                <img src="/images/klasicna-muzika.jpg" alt="Klasična muzika" class="w-full h-auto" />
            </div>
            <div class="flex">
                <img src="/images/slika-andjeli.jpg" alt="Umetnička ilustracija - Anđeli" class="w-full object-cover rounded-lg shadow-md" />
            </div>
        </section>

        <!-- Welcome Section -->
        <section class="-mb-44">
            <h2 class="text-lg font-bold mb-1 uppercase tracking-wide flex items-center whitespace-nowrap">
                <span class="w-8 h-1 bg-radio-red mr-3"></span>
                O nama
            </h2>
            <hr v-if="false" class="text-gray-300 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mt-4">
                <div class="lg:col-span-2">
                    <div class="aspect-square overflow-hidden flex flex-col mb-4">
                        <img src="/images/gustavo-dudamel.jpg" alt="Gustavo Dudamel" class="w-full h-64 object-cover rounded-lg shadow-md" />
                        <span class="text-gray-500 mt-2 text-sm italic">Gustavo Dudamel - direktor Njujorške filharmonije</span>
                    </div>
                </div>
                <div class="lg:col-span-2 prose max-w-none">
                    <h1 class="text-2xl mb-7 inline-block">Dobro došli na <b>Standard</b><span class="text-red-700">Classic</span> Radio</h1>
                    <p class="text-lg text-gray-700 leading-relaxed mb-7">
                        Naš cilj je da vratimo kulturu na velika vrata u regionu, ne samo kroz klasičnu muziku, nego i kroz priču o filmovima, stripu, starim gramofonskim pločama, slikarstvu, pozorištu, ali i da pratimo svakodnevna dešavanja u redovnom životu.
                    </p>
                    <p class="text-lg text-radio-red font-bold hover:underline cursor-pointer">
                        Pročitajte više o nama i našim planovima &rarr;
                    </p>
                </div>
            </div>
        </section>

        <!-- Culture News & Comic Section -->
        <section v-if="false" class="mb-20 grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Culture News -->
            <div>
                <h2 class="text-2xl font-bold mb-8 uppercase tracking-wider flex items-center">
                    <span class="w-8 h-1 bg-radio-red mr-3"></span>
                    Vijesti iz kulture
                </h2>
                <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="bg-gray-200 h-64 flex items-center justify-center">
                        <p class="text-gray-400 italic">[Slika: Violinistkinja]</p>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3">Virtuoznost na sceni: Koncertni spektakl u Narodnom pozorištu</h3>
                        <p class="text-gray-600 mb-4">
                            Mlada zvezda svetske violine oduševila je publiku svojim izvođenjem Čajkovskog...
                        </p>
                        <button class="text-radio-red font-bold hover:underline">Pročitaj više &rarr;</button>
                    </div>
                </div>
            </div>

            <!-- Comic / Iznogud -->
            <div>
                <h2 class="text-2xl font-bold mb-8 uppercase tracking-wider flex items-center">
                    <span class="w-8 h-1 bg-radio-red mr-3"></span>
                    Strip kutak
                </h2>
                <div class="bg-gray-100 p-8 rounded-xl">
                    <div class="bg-gray-300 aspect-video rounded-lg mb-6 flex items-center justify-center">
                        <p class="text-gray-500 italic">[Strip: Iznogud]</p>
                    </div>
                    <h3 class="text-xl font-bold mb-4 uppercase">Iznogud: Želja da se bude kalif umesto kalifa</h3>
                    <p class="text-gray-700">
                        Ove nedelje pratimo nove spletke najpoznatijeg velikog vezira u potrazi za moći...
                    </p>
                </div>
            </div>
        </section>

        <!-- Bottom Grid -->
        <section v-if="true" class="grid grid-cols-1 lg:grid-cols-2 gap-16 pb-12">
            <!-- Daily News -->
            <div>
                <h2 class="text-lg font-bold mb-1 uppercase tracking-wide flex items-center whitespace-nowrap">
                    <span class="w-8 h-1 bg-radio-red mr-3"></span>
                    Vijesti iz dnevno-političkog života
                </h2>
                <ul class="space-y-4 mt-6">
                    <li v-for="news in dailyNews" :key="news.date" class="border-b border-gray-300 pb-4 last:border-0 flex items-center gap-6">
                        <span class="text-radio-red text-sm font-bold block">{{ news.date }}</span>
                        <a href="#" class="text-base font-medium hover:text-radio-red transition">{{ news.title }}</a>
                    </li>
                </ul>
            </div>

            <!-- Podcasts & Shows -->
            <div>
                <h2 class="text-lg font-bold mb-1 uppercase tracking-wide flex items-center whitespace-nowrap">
                    <span class="w-8 h-1 bg-radio-red mr-3"></span>
                    Podkasti i emisije
                </h2>
                <div class="bg-black text-white p-8 rounded-xl flex flex-col md:flex-row gap-8 items-center">
                    <div class="bg-gray-700 w-32 h-32 rounded-full flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <p class="text-[10px] text-gray-400 text-center px-2">[Aljoša Ljubojević]</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Razgovori o duhovnosti</h3>
                        <p class="text-gray-400 text-sm mb-4 italic">Autor: Aljoša Ljubojević</p>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            Svakog utorka istražujemo duboka pitanja ljudskog postojanja i duhovne muzike.
                        </p>
                        <button class="border border-white px-6 py-2 rounded-full hover:bg-white hover:text-black transition uppercase text-xs font-bold">Poslušaj epizodu</button>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>
