import { computed, ref } from 'vue'
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
            // Opciono: obavestiti server o promeni jezika preko sesije (Inertia reload ili axios)
        }
    }

    return {
        t,
        setLang,
        currentLang: computed(() => currentLang.value),
    }
}
