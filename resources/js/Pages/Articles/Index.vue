<template>
    <Head :title="category.name" />

    <div class="py-12">
        <h2 class="mb-8 flex items-center text-lg font-bold tracking-wide uppercase">
            <span class="bg-radio-red mr-3 h-1 w-8 shrink-0"></span>
            {{ category.name }}
        </h2>

        <div class="grid grid-cols-1 gap-12">
            <div v-for="article in articles.data" :key="article.id" class="flex flex-col md:flex-row overflow-hidden">
                <div class="h-64 md:w-2/5 shrink-0 overflow-hidden">
                    <img
                        v-if="article.image"
                        :src="article.image"
                        :alt="article.title || ''"
                        class="h-full w-full object-cover hover:scale-105 transition-transform duration-600 hover:cursor-pointer"
                        @click="$inertia.visit(route('vijest', { slug: article.slug }))" />
                    <div v-else class="flex h-full items-center justify-center">
                        <p class="text-xs text-gray-400 italic">[Nema slike]</p>
                    </div>
                </div>
                <div class="flex w-full flex-col justify-between lg:px-6 py-2 lg:py-1">
                    <div>
                        <Link
                            :href="route('vijest', { slug: article.slug })"
                            class="hover:text-radio-red text-base font-medium transition">
                            <h3 class="mb-1 line-clamp-2 text-xl font-bold" :title="article.title || ''">
                                {{ article.title }}
                            </h3>
                        </Link>
                        <span class="text-xs text-gray-500">{{ article.published_at }}</span>
                        <div class="my-4 line-clamp-6 text-sm text-gray-600" v-html="article.content"></div>
                    </div>
                    <div class="flex items-center">
                        <Link
                            :href="route('vijest', { slug: article.slug })"
                            class="text-radio-red text-left text-sm font-bold hover:cursor-pointer hover:underline">
                            {{ t('read_more') }} &rarr;
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="articles.data.length === 0" class="text-center py-12 text-gray-500">
            {{ t('no_articles') }}
        </div>

        <div class="mt-12 flex justify-center">
            <Pagination :links="articles.meta.links" />
        </div>
    </div>
</template>

<script setup lang="ts">
import Pagination from '@/Components/Pagination.vue'
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Article } from '@/types'
import { Head, Link } from '@inertiajs/vue3'

const { t } = useTrans()

defineOptions({ layout: MainLayout })

defineProps<{
    articles: {
        data: Article[]
        links: any
        meta: {
            links: Array<{
                url: string | null
                label: string
                active: boolean
            }>
        }
    }
    category: {
        id: number
        name: string
        slug: string
    }
}>()
</script>
