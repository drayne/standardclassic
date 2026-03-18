<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head title="Impressum" />

    <div>
        <div class="py-6">
            <div class="text-lg leading-relaxed text-gray-700 font-medium">
                <img
                    src="/images/impressum.jpg"
                    alt="impressum"
                    class="w-full lg:w-1/2 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5" />
                <div class="mb-5 pt-4">
                    <span>
                        StandardClassic.ba internet radio je vlasništvo korporacije "StandardPrva" d.o.o. Bijeljina.
                    </span>
                </div>
                <div class="mb-5">
                    <span>
                        Direktor: Slaviša Lakić
                        <br />
                        Predsjednik Nadzornog odbora: Miloš Stevanović
                    </span>
                </div>
                <div class="mb-5">
                    <span>Glavni i odgovorni urednik: Miloš Stevanović</span>
                </div>
                <div class="mb-5">
                    <span>Urednik sajta: Danka Zakić</span>
                </div>
                <div class="mb-5">
                    <img src="/images/standardprva-logo.png" alt="standardprva logo" class="h-12 w-auto" />
                    <a class="hover:cursor-pointer hover:underline" href="https://standardprva.ba" target="_blank">
                        www.standardprva.ba
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
