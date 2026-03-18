<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })
</script>

<template>
    <Head title="Emisije na našem radiju" />

    <div>
        <div class="py-12">
            <div class="text-lg leading-relaxed text-gray-700">
                <img
                    src="/images/emisije-na-nasem-radiju.jpg"
                    alt="Zasto postojimo"
                    class="w-full lg:w-1/3 h-auto float-none lg:float-right lg:ml-10 mb-10 lg:mb-5"
                />
                <h1 class="text-2xl font-medium text-black mb-6">
                    Emisije na našem radiju
                </h1>
                <p class="mb-5">
                    <b>Standard</b
                    ><span class="text-red-700">Classic</span> Radio od samog
                    početka gradi program koji je ozbiljan, informativan i
                    kulturno utemeljen. Naš cilj nije samo da budemo radio koji
                    emituje muziku, već medij koji oblikuje javni prostor kroz
                    sadržaj koji ima težinu, mjeru i odgovornost.
                </p>

                <p class="mb-5">
                    U početnoj fazi rada planirane su dnevno-političke emisije
                    koje će svakodnevno donositi pregled najvažnijih dešavanja u
                    zemlji i inostranstvu. Emisije će biti koncipirane kao
                    jasan, analitički i smiren pregled vijesti, bez
                    senzacionalizma, sa posebnim naglaskom na razumijevanje
                    konteksta i dugoročnih posljedica događaja. Slušaoci će
                    imati priliku da na jednom mjestu dobiju strukturisane
                    informacije o političkim, ekonomskim i društvenim
                    kretanjima, kako u Bosni i Hercegovini, tako i na
                    regionalnom i međunarodnom nivou.
                </p>

                <p class="mb-5">
                    Posebno mjesto zauzimaće kultura. Jednom sedmično
                    emitovaćemo emisiju pod nazivom KultExpress - kratak, ali
                    sadržajan pregled kulturnih dešavanja u Republici Srpskoj,
                    Federaciji Bosne i Hercegovine i Republici Srbiji.
                    KultExpress će pratiti pozorišne premijere, koncerte,
                    izložbe, književne večeri, filmske festivale i druge
                    značajne kulturne događaje, sa ciljem da afirmiše
                    stvaralaštvo i poveže kulturnu scenu regiona. Emisija će
                    biti dinamična, pregledna i informativna - prava kulturna
                    stanica u vremenu ubrzanih vijesti.
                </p>

                <p class="mb-5">
                    Kako radio bude razvijao svoje tehničke i kadrovske
                    kapacitete, planirano je uvođenje dodatnih specijalizovanih
                    emisija. U budućnosti ćemo širiti program na teme ekonomije,
                    prava, obrazovanja, istorije kao i posebne tematske serijale
                    posvećene umjetnosti i društvenim pitanjima. Naš razvoj će
                    biti postepen, ali strateški osmišljen, sa jasnom ambicijom
                    da <b>Standard</b
                    ><span class="text-red-700">Classic</span> Radio postane
                    relevantan medijski glas kulture i ozbiljnog dijaloga.
                </p>

                <p class="mb-5">
                    <b>Standard</b
                    ><span class="text-red-700">Classic</span> Radio -
                    <b>Informacija sa mjerom.</b> <br />
                    Kultura sa stavom. <br />
                    <b>Program koji raste zajedno sa svojim slušaocima.</b>
                </p>

                <div
                    class="mt-12 border-t border-gray-300 pt-8 flex flex-col gap-8 lg:gap-64 lg:flex-row lg:items-center lg:align-middle"
                >
                    <div class="flex flex-col gap-2">
                        <span class="font-bold">Emisija: KultExpress</span>
                        <span class="font-bold underline"
                            >Pogledajte link emisije sa arhivom snimaka</span
                        >
                    </div>
                    <img
                        src="/images/logo-kult-express.jpg"
                        alt="KultExpress"
                        class="h-72 w-auto"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
