<script setup>
import Footer from '@/Components/Footer.vue'
import Header from '@/Components/Header.vue'
</script>

<template>
    <div class="flex min-h-screen flex-col font-sans text-gray-900 antialiased">
        <Header />

        <main class="mx-auto w-full max-w-6xl flex-grow px-4 py-8">
            <slot />
        </main>

        <Footer />
    </div>
</template>
