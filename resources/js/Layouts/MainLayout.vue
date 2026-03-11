<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col font-sans text-gray-900 antialiased">
        <Header />

        <main class="flex-grow max-w-6xl mx-auto px-4 py-8 w-full">
            <slot />
        </main>

        <Footer />
    </div>
</template>
