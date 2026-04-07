<template>
    <Head :title="article.title || 'Vijest'" />

    <div class="pb-6">
        <article>
            <div v-if="article.image" class="mb-8 ml-12 w-full float-right sm:w-1/2 md:w-3/7">
                <img
                    :src="article.image"
                    :alt="article.title || ''"
                    class="h-auto w-full rounded-sm object-cover shadow-sm" />
            </div>

            <h1 class="mb-4 text-3xl font-bold leading-tight md:text-4xl">
                {{ article.title }}
            </h1>

            <div class="mb-6 flex items-center text-sm text-gray-600">
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                {{ article.published_at }}
            </div>

            <div class="prose max-w-none text-gray-800" v-html="article.content"></div>

            <div class="mt-12 clear-both border-t border-gray-300 pt-8">
                <Link href="/" class="text-radio-red text-sm font-bold hover:underline">
                    &larr; {{ t('back_home') }}
                </Link>
            </div>
        </article>
    </div>
</template>

<script setup lang="ts">
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Article } from '@/types'
import { Head, Link } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })

defineProps<{
    article: Article
}>()
</script>
<style scoped>
.prose :where(h1):not(:where([class~='not-prose'] *)) {
    font-size: 2.25em;
    font-weight: 800;
    margin-top: 0;
    margin-bottom: 0.8888889em;
    line-height: 1.1111111;
}

.prose :where(h2):not(:where([class~='not-prose'] *)) {
    font-size: 1.3em;
    font-weight: 700;
    margin-top: 2em;
    line-height: 1.3333333;
}

.prose :where(h3):not(:where([class~='not-prose'] *)) {
    font-size: 1.1em;
    font-weight: 600;
    margin-top: 1.6em;
    margin-bottom: 0.6em;
    line-height: 1.6;
}

.prose :where(p):not(:where([class~='not-prose'] *)) {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
}

.prose :where(ul):not(:where([class~='not-prose'] *)) {
    list-style-type: disc;
    margin-top: 1.25em;
    margin-bottom: 1.25em;
    padding-left: 1.625em;
}

.prose :where(ol):not(:where([class~='not-prose'] *)) {
    list-style-type: decimal;
    margin-top: 1.25em;
    margin-bottom: 1.25em;
    padding-left: 1.625em;
}

.prose :where(li):not(:where([class~='not-prose'] *)) {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}

.prose :where(strong):not(:where([class~='not-prose'] *)) {
    font-weight: 700;
}
</style>
