<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header class="bg-white py-6">
        <div class="max-w-6xl mx-auto px-4 flex items-end justify-between">
            <!-- Logo s leve strane -->
            <div class="flex-shrink-0">
                <Link href="/" class="block">
                    <img src="/images/logo.png" alt="Standard Classic Radio" class="h-28 w-auto" />
                </Link>
            </div>

            <!-- Desni blok sa dva reda -->
            <div class="flex flex-col items-end gap-4 w-full">
                <!-- Gornji red: Jezici i Trenutno na programu -->
                <div class="flex items-center gap-8 pb-2 w-full justify-end">
                    <div class="text-right leading-tight border-r pr-8 border-gray-200">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500">Trenutno na programu:</p>
                        <p class="font-bold text-sm">J.S. Bach <span class="font-normal italic text-xs text-gray-600 ml-2">Opus B118</span></p>
                    </div>

                    <div class="flex gap-4 text-xs lowercase font-medium">
                        <button class="hover:text-red-700 transition">english</button>
                        <button class="hover:text-red-700 transition">deutsch</button>
                        <button class="font-bold underline text-red-700">srpski</button>
                    </div>
                </div>

                <!-- Donji red: Navigacioni meni -->
                <nav class="hidden lg:flex items-center gap-8">
                    <Link href="#" class="text-base font-normal hover:text-red-700 transition">program</Link>
                    <Link href="#" class="text-base font-normal hover:text-red-700 transition">emisije</Link>
                    <Link href="#" class="text-base font-normal hover:text-red-700 transition">podkasti</Link>
                    <Link href="#" class="text-base font-normal hover:text-red-700 transition">zašto postojimo</Link>
                    <Link
                        href="#"
                        class="bg-[#b31b1b] text-white px-5 py-1 text-base font-medium hover:opacity-80 transition duration-300 rounded-full"
                    >
                        slušaj uživo
                    </Link>
                </nav>
            </div>
        </div>
    </header>
</template>

<style scoped>
/* Ako želiš da font bude identičan onom sa slike (Serif stil),
   možeš dodati font-family ovde ili u Tailwind config */
nav a {
    font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
}
</style>
