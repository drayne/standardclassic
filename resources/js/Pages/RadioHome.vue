<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

const tags = [
    'Klasična muzika', 'Filmovi', 'Knjige', 'Umetnost', 'Kultura', 'Radio',
    'Koncerti', 'Opera', 'Balet', 'Jazz', 'Pozorište', 'Književnost'
];

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
        <section class="mb-16 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="flex flex-wrap gap-4 text-gray-500 font-medium">
                <span v-for="(tag, index) in tags" :key="index"
                    class="text-xl md:text-2xl lg:text-3xl hover:text-radio-red cursor-default transition uppercase tracking-tighter"
                    :class="index % 3 === 0 ? 'text-black font-bold' : ''">
                    {{ tag }}
                </span>
            </div>
            <div class="relative bg-gray-200 aspect-[4/3] rounded-lg overflow-hidden flex items-center justify-center">
                <p class="text-gray-400 italic">[Umetnička ilustracija]</p>
            </div>
        </section>

        <!-- Welcome Section -->
        <section class="mb-20 grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-1">
                <div class="bg-gray-200 aspect-square rounded-lg overflow-hidden flex items-center justify-center mb-4">
                    <p class="text-gray-400 italic">[Slika: Gustavo Dudamel]</p>
                </div>
            </div>
            <div class="lg:col-span-2 prose max-w-none">
                <h1 class="text-4xl font-bold mb-6 uppercase border-b-4 border-radio-red inline-block">Dobrodošli na Standard Classic</h1>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    Muzika je najviša forma komunikacije koja prevazilazi reči i granice. Na našem radiju, svakodnevno vam donosimo najlepše kompozicije svetskih majstora, od baroka do savremenog doba.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed">
                    Verujemo da klasična muzika nije stvar prošlosti, već živahna, inspirativna snaga koja nas povezuje sa dubljim ljudskim emocijama. Pridružite nam se u istraživanju lepote zvuka.
                </p>
            </div>
        </section>

        <!-- Culture News & Comic Section -->
        <section class="mb-20 grid grid-cols-1 lg:grid-cols-2 gap-16">
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
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-16 pb-12">
            <!-- Daily News -->
            <div>
                <h2 class="text-2xl font-bold mb-8 uppercase tracking-wider flex items-center">
                    <span class="w-8 h-1 bg-radio-red mr-3"></span>
                    Vijesti iz dnevno-političkog života
                </h2>
                <ul class="space-y-4">
                    <li v-for="news in dailyNews" :key="news.date" class="border-b pb-4 last:border-0">
                        <span class="text-radio-red text-sm font-bold block mb-1">{{ news.date }}</span>
                        <a href="#" class="text-lg font-medium hover:text-radio-red transition">{{ news.title }}</a>
                    </li>
                </ul>
            </div>

            <!-- Podcasts & Shows -->
            <div>
                <h2 class="text-2xl font-bold mb-8 uppercase tracking-wider flex items-center">
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
