<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head title="Podkasti na Standardclassic Radiju" />

    <div>
        <div class="py-6">
            <div class="text-lg leading-relaxed text-gray-700">
                <img
                    src="/images/podkasti-na-standardclassic-radiju.jpg"
                    alt="podkasti"
                    class="w-full lg:w-1/2 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5" />
                <h1 class="text-2xl font-medium text-black mb-6 pt-4">
                    Podkasti na
                    <b>Standard</b>
                    <span class="text-red-700">Classic</span>
                    Radiju
                </h1>
                <p class="mb-5">
                    Podkasti na
                    <b>Standard</b>
                    <span class="text-red-700">Classic</span>
                    radiju predstavljaju prostor za dublji razgovor i tematsku analizu. Trenutno su u programu dva
                    podkasta. „Gdje se fura (ne)kultura“ bavi se savremenim kulturnim fenomenima, umjetnošću i
                    društvenim trendovima iz sfere kulture, dok „Miloš Stevanović podkast“ donosi razgovore i
                    promišljanja iz oblasti biznisa, strategije i preduzetništva. Vremenom, paleta podkasta će se širiti
                    u skladu sa rastom radija i interesovanjem publike.
                </p>
            </div>

            <div class="mt-10 lg:flex items-start w-full p-6 rounded-lg">
                <div class="lg:w-1/3 mb-6 lg:mb-0 lg:mr-10 pt-2">
                    <img src="/images/aljosa-logo-crop.jpg" alt="aljosa-logo" class="w-full h-auto rounded shadow-sm" />
                </div>
                <div class="lg:w-2/3 text-lg leading-relaxed text-gray-700">
                    <h2 class="text-xl font-medium text-black mb-4">Gdje se fura (ne)kultura</h2>
                    <p class="mb-4">
                        Riječ je o studijskoj emisiji koja podrazumijeva razgovor voditelja i jednog gosta, sa fokusom
                        na promišljanje savremenog odnosa prema kulturi i (ne)kulturi. Iako je Standard Classic Radio
                        primarno posvećen klasičnoj muzici, ova emisija tematski proširuje prostor djelovanja Radija na
                        šire kulturne oblasti.
                    </p>
                    <p class="mb-4">
                        Emisija je posvećena muzici, književnosti, likovnoj umjetnosti, stripu, pozorištu i drugim
                        segmentima kulturnog stvaralaštva, kao i analizi društvenog konteksta u kojem kultura nastaje i
                        razvija se. Gosti emisije biće akademski građani iz Bijeljine, Republike Srpske, cijele Bosne i
                        Hercegovine i Srbije – profesori muzike, pisci, slikari, glumci i drugi kulturni djelatnici –
                        koji će govoriti o svojoj profesiji, estetskim opredjeljenjima i ličnom razumijevanju kulture.
                    </p>
                    <p class="mb-4">
                        U okviru emisije biće emitovane i muzičke numere koje gost prethodno odabere, vodeći računa o
                        estetskom kriterijumu i umjetničkom nivou.
                    </p>
                    <p class="mb-4">
                        „Gdje se fura (ne)kultura“ predstavlja opredjeljenje Standard Classic Radija ka njegovanju
                        ozbiljnog, argumentovanog i kulturno odgovornog medijskog prostora.
                    </p>
                </div>
            </div>

            <div class="mt-12 lg:flex items-start w-full p-6 rounded-lg">
                <div class="lg:w-1/3 mb-6 lg:mb-0 lg:mr-10">
                    <img
                        src="/images/milos-stevanovic-podcast.jpg"
                        alt="milos-stevanovic-podcast"
                        class="w-full h-auto rounded shadow-sm" />
                </div>
                <div class="lg:w-2/3 text-lg leading-relaxed text-gray-700">
                    <h2 class="text-xl font-medium text-black mb-4">Miloš Stevanović Standard Podkast</h2>
                    <p class="mb-4">
                        Podcast Miloša Stevanovića, vlasnika StandardPrva Grupacije, te vrlo popularnog autora knjige
                        &quot;Let bez prestanka&quot;, te interent zvijezde koji se često pojavljuje u emisijama koje su
                        vezane za lično napredovanje, ovaj put donose nešto sasvim novo.
                    </p>
                    <p class="mb-4">
                        Miloš Stevanović se pojavljuje u ulozi voditelja emisije, te sa svojim gostima priča o biznisu,
                        sagledava teme iz poslovnog, ekonomskog i političkog aspekta u zemlji i regionu sa donošenjem
                        zaključaka koji su održivi i koji će putem ekosistema StandardPrve imati svoj odijek.
                    </p>
                    <p class="mb-4">
                        Emisija će se emitovati jednom mjesečno na radiju StandardClassic i njena arhiva biće u cjelosti
                        smještena na sajtu radija.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
