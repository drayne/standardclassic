<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head title="Miloš Stevanović Standard Classic podcast" />

    <div>
        <div class="py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <img
                    src="/images/milos-stevanovic-podcast.jpg"
                    alt="Milos podcast logo"
                    class="w-auto h-auto aspect-auto" />

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <h1 class="inline-block text-2xl font-medium">Standard Classic podcast</h1>
                        <p class="text-radio-red mb-3 text-base font-bold italic">Autor i voditelj: Miloš Stevanović</p>
                    </div>
                    <p class="-mt-2 text-lg leading-relaxed text-gray-700">
                        Podcast Miloša Stevanovića, vlasnika StandardPrva Grupacije, te vrlo popularnog autora knjige
                        &quot;Let bez prestanka&quot;, te interent zvijezde koji se često pojavljuje u emisijama koje su
                        vezane za lično napredovanje, ovaj put donose nešto sasvim novo.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        Miloš Stevanović se pojavljuje u ulozi voditelja emisije, te sa svojim gostima priča o biznisu,
                        sagledava teme iz poslovnog, ekonomskog i političkog aspekta u zemlji i regionu sa donošenjem
                        zaključaka koji su održivi i koji će putem ekosistema StandardPrve imati svoj odijek.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        Emisija će se emitovati jednom mjesečno na radiju StandardClassic i njena arhiva biće u cjelosti
                        smještena na sajtu radija.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
