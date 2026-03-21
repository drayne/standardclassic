import { router, usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'
import de from '../lang/de'
import en from '../lang/en'
import sr from '../lang/sr'

const translations = {
    sr,
    en,
    de,
}

const currentLang = ref(localStorage.getItem('lang') || 'sr')

export function useTrans() {
    const page = usePage()

    const syncWithBackend = () => {
        const lang = currentLang.value
        if (page.props.locale !== lang) {
            router.post(
                route('language'),
                { lang },
                {
                    preserveScroll: true,
                },
            )
        }
    }

    onMounted(() => {
        syncWithBackend()
    })

    const t = (key) => {
        const keys = key.split('.')
        let result = translations[currentLang.value]
        for (const k of keys) {
            if (result && result[k]) {
                result = result[k]
            } else {
                return key
            }
        }
        return result
    }

    const setLang = (lang) => {
        if (translations[lang]) {
            currentLang.value = lang
            localStorage.setItem('lang', lang)
            router.post(
                route('language'),
                { lang },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        // Opciono: refresh podataka ako je potrebno, ali Inertia reload-uje po defaultu
                    },
                },
            )
        }
    }

    return {
        t,
        setLang,
        currentLang: computed(() => currentLang.value),
    }
}
