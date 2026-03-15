<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <header class="bg-white py-6">
        <div class="mx-auto flex max-w-6xl items-end justify-between px-4">
            <!-- Logo s leve strane -->
            <div class="flex-shrink-0">
                <Link href="/" class="block">
                    <img
                        src="/images/logo.png"
                        alt="Standard Classic Radio"
                        class="h-28 w-auto"
                    />
                </Link>
            </div>

            <!-- Desni blok sa dva reda -->
            <div class="flex w-full flex-col items-end gap-4">
                <!-- Gornji red: Jezici i Trenutno na programu -->
                <div class="flex w-full items-center justify-end gap-8 pb-2">
                    <div
                        class="border-r border-gray-200 pr-8 text-right leading-tight"
                    >
                        <p
                            class="text-[10px] tracking-wider text-gray-500 uppercase"
                        >
                            Trenutno na programu:
                        </p>
                        <p class="text-sm font-bold">
                            J.S. Bach
                            <span
                                class="ml-2 text-xs font-normal text-gray-600 italic"
                                >Opus B118</span
                            >
                        </p>
                    </div>

                    <div class="flex gap-4 text-xs font-medium lowercase">
                        <button class="transition hover:text-red-700">
                            english
                        </button>
                        <button class="transition hover:text-red-700">
                            deutsch
                        </button>
                        <button class="font-bold text-red-700 underline">
                            srpski
                        </button>
                    </div>
                </div>

                <!-- Donji red: Navigacioni meni -->
                <nav class="hidden items-center gap-8 lg:flex">
                    <Link
                        :href="route('program-radija')"
                        class="text-base font-normal transition hover:text-red-700"
                        >program</Link
                    >
                    <Link
                        href="#"
                        class="text-base font-normal transition hover:text-red-700"
                        >emisije</Link
                    >
                    <Link
                        href="#"
                        class="text-base font-normal transition hover:text-red-700"
                        >podkasti</Link
                    >
                    <Link
                        :href="route('zasto-postojimo')"
                        class="text-base font-normal transition hover:text-red-700"
                        >zašto postojimo</Link
                    >
                    <Link
                        href="#"
                        class="rounded-full bg-[#b31b1b] px-5 py-1 text-base font-medium text-white transition duration-300 hover:opacity-80"
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
    font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;
}
</style>
