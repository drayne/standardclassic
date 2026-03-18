<template>
    <Head :title="article.title || 'Vijest'" />

    <div class="pb-6">
        <article>
            <div
                v-if="article.image"
                class="mb-8 ml-12 w-full float-right sm:w-1/2 md:w-3/7"
            >
                <img
                    :src="article.image"
                    :alt="article.title || ''"
                    class="h-auto w-full rounded-sm object-cover shadow-sm"
                />
            </div>

            <h1 class="mb-4 text-3xl font-bold leading-tight md:text-4xl">
                {{ article.title }}
            </h1>

            <div class="mb-6 flex items-center text-sm text-gray-600">
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                {{ article.published_at }}
            </div>

            <div
                class="prose max-w-none text-gray-800"
                v-html="article.content"
            ></div>

            <div class="mt-12 border-t border-gray-300 pt-8">
                <Link
                    href="/"
                    class="text-radio-red text-sm font-bold hover:underline"
                >
                    &larr; Povratak na početnu
                </Link>
            </div>
        </article>
    </div>
</template>

<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { Article } from '@/types'
import { Head, Link } from '@inertiajs/vue3'

defineOptions({ layout: MainLayout })

defineProps<{
    article: Article
}>()
</script>
