<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-radio-red text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Logo & Slogan -->
                <div class="flex flex-col">
                    <div class="mb-4">
                        <Link href="/" class="text-2xl font-bold tracking-tighter text-white">
                            STANDARD <span class="opacity-80">CLASSIC</span> RADIO
                        </Link>
                    </div>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Radio stanica posvećena klasičnoj muzici i kulturnim vrednostima. Naša misija je edukacija i promocija pravih umetničkih standarda.
                    </p>
                </div>

                <!-- Radio Links -->
                <div>
                    <h3 class="font-bold text-lg mb-4 uppercase tracking-wider">Radio Links</h3>
                    <ul class="space-y-2 text-sm opacity-90">
                        <li><Link href="#" class="hover:underline transition">O nama</Link></li>
                        <li><Link href="#" class="hover:underline transition">Programska šema</Link></li>
                        <li><Link href="#" class="hover:underline transition">Arhiva emisija</Link></li>
                        <li><Link href="#" class="hover:underline transition">Podkasti</Link></li>
                        <li><Link href="#" class="hover:underline transition">Kontakt</Link></li>
                    </ul>
                </div>

                <!-- Corporate Info / Copyright -->
                <div>
                    <h3 class="font-bold text-lg mb-4 uppercase tracking-wider">Informacije</h3>
                    <div class="text-sm opacity-90 space-y-2">
                        <p>Bulevar umetnosti 12, 11000 Beograd</p>
                        <p>Email: info@standardclassic.rs</p>
                        <p>Telefon: +381 11 123 4567</p>
                    </div>
                </div>
            </div>

            <!-- Copyright Line -->
            <div class="mt-12 pt-8 border-t border-white/20 text-xs text-center opacity-70">
                &copy; {{ new Date().getFullYear() }} Standard Classic Radio. Sva prava zadržana.
            </div>
        </div>
    </footer>
</template>
