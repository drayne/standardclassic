<template>
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
                Gdje se fura (ne)kultura je naša najbolja emisija gdje slušaoci i gledaoci mogu da prate odličan podkast
                serija koji vodi Aljoša Ljubojević, poznati novinar iz Bijeljine. Aljoša će razgovarati sa brojnim
                kulturnim radnicima, ali i poznavaocima filma, sporta, dobre muzike, stripova i mnogo toga.
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
</template>

<script setup lang="ts"></script>
