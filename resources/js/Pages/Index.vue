<template>
    <Head :title="t('index_page.title')" />

    <div>
        <!-- Hero Section -->
        <section class="mb-16 grid grid-cols-1 items-stretch gap-8 lg:grid-cols-2">
            <div class="flex items-center justify-center">
                <img :src="leftImage" alt="Klasična muzika" class="h-auto w-full" />
            </div>
            <div class="flex">
                <img :src="rightImage" alt="Umetnička ilustracija - Anđeli" class="w-full rounded-lg object-cover" />
            </div>
        </section>

        <!-- Welcome Section -->
        <section class="mb-16">
            <h2 class="mb-2 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                {{ t('index_page.about_us') }}
            </h2>
            <div class="mt-4 grid grid-cols-1 gap-12 lg:grid-cols-2">
                <div class="lg:col-span-1">
                    <div class="mb-4 flex flex-col overflow-hidden">
                        <img
                            src="/images/gustavo-dudamel.jpg"
                            alt="Gustavo Dudamel"
                            @click="
                                openExternalLink(
                                    'https://sr.wikipedia.org/sr-ec/%D0%93%D1%83%D1%81%D1%82%D0%B0%D0%B2%D0%BE_%D0%94%D1%83%D0%B4%D0%B0%D0%BC%D0%B5%D0%BB',
                                )
                            "
                            class="h-64 w-full rounded-lg object-cover hover:cursor-pointer hover:shadow-md transition duration-300" />
                        <span class="mt-2 text-sm text-gray-500 italic">
                            {{ t('index_page.dudamel_desc') }}
                        </span>
                    </div>
                </div>
                <div class="prose max-w-none lg:col-span-1">
                    <h1 class="mb-7 inline-block text-2xl" v-html="t('index_page.welcome_h1')"></h1>
                    <p class="mb-7 text-lg leading-relaxed text-gray-700">
                        {{ t('index_page.welcome_p') }}
                    </p>
                    <Link
                        class="text-radio-red cursor-pointer text-lg font-bold hover:underline"
                        :href="route('zasto-postojimo')">
                        <span v-html="t('index_page.read_more_about_us')"></span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Culture News Section -->
        <section class="mb-16">
            <h2 class="mb-3 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                <span class="bg-radio-red mr-3 h-1 w-8"></span>
                {{ t('index_page.culture_news') }}
            </h2>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div
                    v-for="article in kulturaArticles"
                    :key="article.id"
                    class="flex overflow-hidden rounded-md border border-gray-200 bg-white transition hover:shadow-md hover:cursor-pointer"
                    @click="$inertia.visit(`/vijest/${article.slug}`)">
                    <div class="h-64 w-2/5 flex-shrink-0">
                        <img
                            v-if="article.image"
                            :src="article.image"
                            :alt="article.title || ''"
                            class="h-full w-full object-cover" />
                        <div v-else class="flex h-full items-center justify-center">
                            <p class="text-xs text-gray-400 italic">{{ t('index_page.no_image') }}</p>
                        </div>
                    </div>
                    <div class="flex w-full flex-col justify-between p-6">
                        <div>
                            <h3 class="mb-2 line-clamp-2 text-lg font-bold">
                                {{ article.title }}
                            </h3>
                            <div class="mb-4 line-clamp-4 text-sm text-gray-600" v-html="article.content"></div>
                        </div>
                        <button class="text-radio-red text-left text-sm font-bold hover:cursor-pointer">
                            <span v-html="t('index_page.read_more')"></span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom Grid -->
        <section class="grid grid-cols-1 gap-16 pb-12 lg:grid-cols-2">
            <!-- Daily News -->
            <div>
                <h2 class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    {{ t('index_page.daily_news') }}
                </h2>
                <ul class="mt-6 space-y-4">
                    <li
                        v-for="article in dpArticles"
                        :key="article.id"
                        class="flex items-center gap-6 border-b border-gray-300 pb-4 last:border-0">
                        <span class="text-radio-red block w-24 shrink-0 whitespace-nowrap text-sm font-bold">
                            {{ article.published_at }}
                        </span>
                        <Link
                            :href="route('vijest', article.slug)"
                            class="hover:text-radio-red truncate text-base font-medium transition">
                            {{ article.title }}
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Podcasts & Shows -->
            <div>
                <h2 class="mb-1 flex items-center text-lg font-bold tracking-wide whitespace-nowrap uppercase">
                    <span class="bg-radio-red mr-3 h-1 w-8"></span>
                    {{ t('index_page.podcasts_and_shows') }}
                </h2>
                <PodcastAljosa v-if="randomPodcastIndex === 1" />
                <PodcastMilos v-if="randomPodcastIndex === 2" />
                <PodcastMilos v-if="randomPodcastIndex === 3" />
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import PodcastAljosa from '@/Components/PodcastAljosa.vue'
import PodcastMilos from '@/Components/PodcastMilos.vue'
import { useTrans } from '@/Composables/useTrans'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Article } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const { t } = useTrans()

defineOptions({ layout: MainLayout })

interface CoverImage {
    id: number
    path: string
    position: 'L' | 'R'
}

const props = defineProps<{
    kulturaArticles: Article[]
    dpArticles: Article[]
    coverImages: CoverImage[]
}>()

const leftImage = computed(() => {
    const img = props.coverImages.find((img) => img.position === 'L')
    return img ? img.path : '/images/klasicna-muzika.jpg'
})

const rightImage = computed(() => {
    const img = props.coverImages.find((img) => img.position === 'R')
    return img ? img.path : '/images/slika-andjeli.jpg'
})

const openExternalLink = (url: string) => {
    if (typeof window !== 'undefined') {
        window.open(url, '_blank')
    }
}

const randomPodcastIndex = computed(() => {
    return Math.floor(Math.random() * 3) + 1
})
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
