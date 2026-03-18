<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head title="Gdje se fura nekultura" />

    <div>
        <div class="py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <img src="/images/aljosa-logo-crop.jpg" alt="Aljosa logo" class="w-auto h-auto aspect-auto mt-2" />

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <h1 class="inline-block text-2xl font-medium">Dobro došli na Gdje se fura (ne)kultura</h1>
                        <p class="text-radio-red mb-3 text-base font-bold italic">
                            Autor i voditelj: Aljoša Ljubojević
                        </p>
                    </div>
                    <p class="-mt-2 text-lg leading-relaxed text-gray-700">
                        Riječ je o studijskoj emisiji koja podrazumijeva razgovor voditelja i jednog gosta, sa fokusom
                        na promišljanje savremenog odnosa prema kulturi i (ne)kulturi. Iako je Standard Classic Radio
                        primarno posvećen klasičnoj muzici, ova emisija tematski proširuje prostor djelovanja Radija na
                        šire kulturne oblasti.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        Emisija je posvećena muzici, književnosti, likovnoj umjetnosti, stripu, pozorištu i drugim
                        segmentima kulturnog stvaralaštva, kao i analizi društvenog konteksta u kojem kultura nastaje i
                        razvija se.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        Gosti emisije biće akademski građani iz Bijeljine, Republike Srpske, cijele Bosne i Hercegovine
                        i Srbije – profesori muzike, pisci, slikari, glumci i drugi kulturni djelatnici – koji će
                        govoriti o svojoj profesiji, estetskim opredjeljenjima i ličnom razumijevanju kulture.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        U okviru emisije biće emitovane i muzičke numere koje gost prethodno odabere, vodeći računa o
                        estetskom kriterijumu i umjetničkom nivou.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700">
                        „Gdje se fura (ne)kultura“ predstavlja opredjeljenje  Standard Classic Radija ka njegovanju
                        ozbiljnog, argumentovanog i kulturno odgovornog medijskog prostora.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
