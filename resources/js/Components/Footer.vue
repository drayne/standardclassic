<script setup>
import { useTrans } from '@/Composables/useTrans'
import { Link } from '@inertiajs/vue3'

const { t } = useTrans()
</script>

<template>
    <footer class="bg-radio-red py-12 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                <!-- Logo & Slogan -->
                <div class="flex flex-col items-center pr-12">
                    <div class="mb-0">
                        <Link href="/">
                            <img src="/images/logo-red.png" alt="Standard Classic Radio" class="h-48 w-auto" />
                        </Link>
                    </div>
                    <p class="text-sm leading-relaxed font-bold opacity-90">{{ t('footer.slogan') }}</p>
                </div>

                <!-- Radio Links -->
                <div>
                    <h3 class="mb-4 text-lg font-bold tracking-wider uppercase">StandardClassic Radio</h3>
                    <ul class="space-y-0.5 text-sm opacity-90">
                        <li>
                            <Link :href="route('program-radija')" class="transition hover:underline">
                                {{ t('footer.radio_program') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('emisije-na-nasem-radiju')" class="transition hover:underline">
                                {{ t('footer.shows') }}
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('podkasti-na-standardclassic-radiju')"
                                class="transition hover:underline">
                                {{ t('footer.podcasts') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('zasto-postojimo')" class="transition hover:underline">
                                {{ t('footer.why_we_exist') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('impressum')" class="transition hover:underline">
                                {{ t('footer.impressum') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('vijesti-iz-kulture')" class="transition hover:underline">
                                {{ t('footer.culture_news') }}
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('vijesti-iz-dnevno-politickog-zivota')"
                                class="transition hover:underline">
                                {{ t('footer.politics_news') }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Corporate Info / Copyright -->
                <div>
                    <img src="/images/sp-logo-red.png" alt="Standard Classic Radio" class="h-12 w-auto" />
                    <span class="text-sm">{{ t('footer.corporate_info') }}</span>
                    <ul class="mt-4 mb-3 space-y-0.5 text-sm opacity-90">
                        <li>
                            <Link href="#" class="transition hover:underline">{{ t('footer.about_corp') }}</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">{{ t('footer.management') }}</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">{{ t('footer.standard_plaza') }}</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">{{ t('footer.luxury_manifesto') }}</Link>
                        </li>
                        <li>
                            <Link href="#" class="transition hover:underline">
                                {{ t('footer.foundation') }}
                            </Link>
                        </li>
                    </ul>

                    <span class="text-xs">
                        {{ t('footer.copyright').replace('{year}', new Date().getFullYear()) }}
                    </span>
                </div>
            </div>
        </div>
    </footer>
</template>
