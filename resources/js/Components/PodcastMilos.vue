<template>
    <div class="mt-6">
        <div
            class="mb-6 flex h-auto w-full items-center justify-center overflow-hidden md:float-left md:mr-8 md:mb-4 md:ml-0 md:w-58">
            <img
                src="/images/milos-stevanovic-podcast.jpg"
                alt="Miloš Stevanović"
                class="h-auto w-full rounded-lg object-contain shadow-sm" />
        </div>
        <div class="flex flex-col overflow-hidden md:overflow-visible">
            <h3 class="mb-1 text-xl font-bold">Standard Podkast</h3>
            <p class="text-radio-red mb-3 text-sm font-bold italic">Autor: Miloš Stevanović</p>
            <p class="mb-3 leading-relaxed text-gray-700">
                Miloš sa svojim gostima priča o biznisu, sagledava teme iz poslovnog, ekonomskog i političkog aspekta u
                zemlji i regionu sa donošenjem zaključaka koji su održivi i koji će putem ekosistema StandardPrve imati
                svoj odijek.
            </p>
            <div class="mt-0">
                <button
                    class="text-radio-red text-base font-bold hover:underline hover:cursor-pointer transition duration-300"
                    @click="$inertia.visit('/Milos-Stevanovic-standard-classic-podcast')">
                    Pogledajte detaljnije &rarr;
                </button>
            </div>
        </div>
        <div class="clear-both"></div>
    </div>
</template>

<script setup lang="ts"></script>
