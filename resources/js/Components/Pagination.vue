<template>
    <div v-if="paginationLinks.length > 0">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in paginationLinks" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" />
                <Link
                    v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded transition-colors duration-200 focus:outline-none focus:border-radio-red focus:text-radio-red"
                    :class="{
                        'bg-radio-red text-white border-radio-red hover:bg-red-700': link.active,
                        'hover:bg-gray-100 hover:border-radio-red': !link.active,
                    }"
                    :href="link.url">
                    <span v-html="link.label"></span>
                </Link>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps<{
    links: Array<{
        url: string | null
        label: string
        active: boolean
    }>
}>()

const paginationLinks = computed(() => {
    return props.links.filter((link) => {
        // Zadržavamo samo linkove čija je labela broj (Laravel pagination šalje brojeve stranica kao stringove "1", "2", itd.)
        // i linkove za "tri tačke" (...) koji nemaju URL ali služe kao separator
        const isNumeric = !isNaN(Number(link.label))
        const isDots = link.label.includes('...')
        return isNumeric || isDots
    })
})
</script>
