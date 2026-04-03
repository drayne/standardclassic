<script setup>
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head title="Popodne sa Acom Informacijom" />

    <div>
        <div class="py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <img
                    src="/images/popodne-sa-acom-informacijom.jpg"
                    alt="Aca Informacija logo"
                    class="w-auto h-auto aspect-auto lg:mt-4" />

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <h1 class="inline-block text-2xl font-medium">Popodne sa Acom Informacijom</h1>
                        <p class="text-radio-red mb-3 text-base font-bold italic">
                            Autor i voditelj: Aleksandar Stojanović
                        </p>
                    </div>
                    <p class="-mt-2 text-lg leading-relaxed text-gray-700">
                        Jedan od prvih autorskih programa koji će se naći u programu radija biće emisija
                        <b>„Popodne sa Acom Informacijom“</b>, koju vodi poznati radijski glas Aca Informacija, odnosno
                        Aleksandar Stojanović iz Beograda. Emisija će se emitovati svakog četvrtka, u opuštenom
                        popodnevnom terminu, namijenjenom svima koji žele da nakon radnog dana čuju zanimljive priče iz
                        svijeta sporta, ali i uživaju u dobroj muzici.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        „Popodne sa Acom Informacijom“ donosi spoj
                        <b>sportskih tema, komentara i analize</b>, ali i lagane atmosfere kroz pažljivo odabranu
                        muziku. Ideja emisije je da sport približi slušaocima na zanimljiv i razgovoran način – bez
                        pretjerane formalnosti, ali uz dovoljno informacija i zanimljivih detalja koji prate domaća i
                        međunarodna sportska dešavanja.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        Pokretanjem ove emisije, StandardClassic pokazuje namjeru da stvori
                        <b>radio koji je istovremeno informativan, kulturan i zabavan</b>, sa autorima koji imaju
                        prepoznatljiv stil i energiju. U narednom periodu očekuje se i pokretanje drugih autorskih
                        emisija koje će obogatiti program radija i donijeti raznovrsne sadržaje – od kulture i ekonomije
                        do sporta i savremenog društva.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        Četvrtak popodne biće rezervisan za „Popodne sa Acom Informacijom“ – emisiju o sportu, muzici i
                        dobrim pričama.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
