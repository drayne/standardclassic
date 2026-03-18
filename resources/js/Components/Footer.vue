<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <footer class="bg-radio-red py-12 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                <!-- Logo & Slogan -->
                <div class="flex flex-col items-center pr-12">
                    <div class="mb-0">
                        <Link href="/">
                            <img src="/images/logo-red.png" alt="Standard Classic Radio" class="h-48 w-auto" />
                        </Link>
                    </div>
                    <p class="text-sm leading-relaxed font-bold opacity-90">Muzika koja inspiriše i povezuje</p>
                </div>

                <!-- Radio Links -->
                <div>
                    <h3 class="mb-4 text-lg font-bold tracking-wider uppercase">StandardClassic Radio</h3>
                    <ul class="space-y-0.5 text-sm opacity-90">
                        <li>
                            <Link :href="route('program-radija')" class="transition hover:underline">
                                Program radija
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('emisije-na-nasem-radiju')" class="transition hover:underline">
                                Emisije
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('podkasti-na-standardclassic-radiju')"
                                class="transition hover:underline">
                                Podkasti
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('zasto-postojimo')" class="transition hover:underline">
                                Zašto postojimo i ko smo mi?
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('impressum')" class="transition hover:underline">Impresum</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">Radio uživo</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">Vijesti iz kulture</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">Vijesti iz dnevno-političkog života</Link>
                        </li>
                    </ul>
                </div>

                <!-- Corporate Info / Copyright -->
                <div>
                    <img src="/images/sp-logo-red.png" alt="Standard Classic Radio" class="h-12 w-auto" />
                    <span class="text-sm">Naša radio stanica ponosna je članica korporacije StandardPrva.</span>
                    <ul class="mt-4 mb-3 space-y-0.5 text-sm opacity-90">
                        <li>
                            <Link href="#" class="transition hover:underline">O korporaciji</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">Menadžment</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">Standard Plaza</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">Our luxury alignment manifesto</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">
                                Fondacija Branislava i Vesne Stevanović
                            </Link>
                        </li>
                    </ul>

                    <span class="text-xs">
                        Copyright © {{ new Date().getFullYear() }} StandardPrva LLC. Zabranjeno kopiranje.
                    </span>
                </div>
            </div>
        </div>
    </footer>
</template>
