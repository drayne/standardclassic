<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-radio-red text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Logo & Slogan -->
                    <div class="flex flex-col items-center pr-12">
                        <div class="mb-0">
                            <Link href="/">
                                <img src="/images/logo-red.png" alt="Standard Classic Radio" class="h-48 w-auto" />
                            </Link>
                        </div>
                        <p class="text-sm opacity-90 leading-relaxed font-bold">
                            Muzika koja inspiriše i povezuje
                        </p>
                    </div>

                <!-- Radio Links -->
                <div>
                    <h3 class="font-bold text-lg mb-4 uppercase tracking-wider">StandardClassic Radio</h3>
                    <ul class="space-y-0.5 text-sm opacity-90">
                        <li><Link href="#" class="hover:underline transition">Program radija</Link></li>
                        <li><Link href="#" class="hover:underline transition">Emisije</Link></li>
                        <li><Link href="#" class="hover:underline transition">Podkasti</Link></li>
                        <li><Link href="#" class="hover:underline transition">Zašto postojimo i ko smo mi?</Link></li>
                        <li><Link href="#" class="hover:underline transition">Impresum</Link></li>
                        <li><Link href="#" class="hover:underline transition">Radio uživo</Link></li>
                        <li><Link href="#" class="hover:underline transition">Vijesti iz kulture</Link></li>
                        <li><Link href="#" class="hover:underline transition">Vijesti iz dnevno-političkog života</Link></li>
                    </ul>
                </div>

                <!-- Corporate Info / Copyright -->
                <div>
                    <img src="/images/sp-logo-red.png" alt="Standard Classic Radio" class="h-12 w-auto" />
                    <span class="text-sm">Naša radio stanica ponosna je članica korporacije StandardPrva.</span>
                    <ul class="mt-4 space-y-0.5 text-sm opacity-90 mb-3">
                        <li><Link href="#" class="hover:underline transition">O korporaciji</Link></li>
                        <li><Link href="#" class="hover:underline transition">Menadžment</Link></li>
                        <li><Link href="#" class="hover:underline transition">Standard Plaza</Link></li>
                        <li><Link href="#" class="hover:underline transition">Our luxury alignment manifesto</Link></li>
                        <li><Link href="#" class="hover:underline transition">Fondacija Branislava i Vesne Stevanović</Link></li>
                    </ul>

                    <span class="text-xs">Copyright © {{ new Date().getFullYear() }} StandardPrva LLC. Zabranjeno kopiranje.</span>
                </div>
            </div>
        </div>
    </footer>
</template>
