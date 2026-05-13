<template>
    <div v-if="paginationLinks.length > 0">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in paginationLinks" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-3 py-2 md:px-4 md:py-3 text-sm leading-4 text-gray-400 border rounded"
                    :class="{
                        'hidden sm:block': link.label.includes('...'),
                        'inline-flex sm:hidden':
                            link.label.toLowerCase().includes('prev') ||
                            link.label.toLowerCase().includes('next') ||
                            link.label.includes('&laquo;') ||
                            link.label.includes('&raquo;'),
                    }"
                    v-html="getLinkLabel(link.label)" />
                <Link
                    v-else
                    class="mr-1 mb-1 px-3 py-2 md:px-4 md:py-3 text-sm leading-4 border rounded transition-colors duration-200 focus:outline-none focus:border-radio-red focus:text-radio-red"
                    :class="{
                        'bg-radio-red text-white border-radio-red hover:bg-red-700': link.active,
                        'hover:bg-gray-100 hover:border-radio-red': !link.active,
                        'hidden sm:inline-flex': !link.active && !isNaN(Number(link.label)),
                        'inline-flex sm:hidden':
                            link.label.toLowerCase().includes('prev') ||
                            link.label.toLowerCase().includes('next') ||
                            link.label.includes('&laquo;') ||
                            link.label.includes('&raquo;'),
                    }"
                    :href="link.url">
                    <span v-html="getLinkLabel(link.label)"></span>
                </Link>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useTrans } from '@/Composables/useTrans'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const { t } = useTrans()

const props = defineProps<{
    links: Array<{
        url: string | null
        label: string
        active: boolean
    }>
}>()

const paginationLinks = computed(() => {
    return props.links
})

const getLinkLabel = (label: string) => {
    if (label.toLowerCase().includes('prev') || label.includes('&laquo;')) {
        return t('previous')
    }
    if (label.toLowerCase().includes('next') || label.includes('&raquo;')) {
        return t('next')
    }
    return label
}
</script>
