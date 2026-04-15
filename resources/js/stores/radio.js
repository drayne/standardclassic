import { reactive } from 'vue'

export const radioStore = reactive({
    isPlaying: false,
    isMuted: false,
    volume: localStorage.getItem('radioVolume') ? parseFloat(localStorage.getItem('radioVolume')) : 0.7,
    previousVolume: 0.7,
    audio: null,
    streamUrl: null,
    currentTrack: {
        title: 'Učitavanje...',
        artist: '',
        composer_name: '',
    },

    init(streamUrl) {
        this.streamUrl = streamUrl
        if (!this.audio) {
            this.audio = new Audio()
            this.audio.preload = 'none'
            this.audio.volume = this.volume

            // Ako strim stane zbog baferovanja
            this.audio.addEventListener('stalled', () => {
                if (this.isPlaying) {
                    console.warn('Strim je stao (stalled), pokušavam oporavak...')
                    this.recover()
                }
            })

            // U slučaju bilo kakve greške na mreži/izvoru
            this.audio.addEventListener('error', () => {
                if (this.isPlaying) {
                    console.error('Greška na audio strimu, pokušavam ponovno povezivanje za 3s...')
                    setTimeout(() => this.recover(), 3000)
                }
            })
        }
    },

    recover() {
        if (!this.audio || !this.streamUrl) return
        const wasPlaying = this.isPlaying

        this.audio.pause()
        this.audio.src = '' // Čišćenje bafera
        this.audio.load()

        if (wasPlaying) {
            this.audio.src = this.streamUrl
            this.audio.play().catch((e) => console.error('Oporavak nije uspio:', e))
        }
    },

    updateVolume(val) {
        this.volume = val
        if (this.audio) {
            this.audio.volume = val
        }
        if (val > 0) {
            this.isMuted = false
        }
        localStorage.setItem('radioVolume', val.toString())
    },

    toggleMute() {
        if (!this.audio) return

        if (this.isMuted) {
            this.updateVolume(this.previousVolume > 0 ? this.previousVolume : 0.7)
            this.isMuted = false
        } else {
            this.previousVolume = this.volume
            this.updateVolume(0)
            this.isMuted = true
        }
    },

    togglePlay() {
        if (!this.audio) return

        if (this.isPlaying) {
            this.audio.pause()
            this.audio.src = ''
            this.audio.load()
            this.isPlaying = false
        } else {
            this.audio.src = this.streamUrl
            this.audio
                .play()
                .then(() => {
                    this.audio.volume = this.volume
                    this.isPlaying = true
                })
                .catch((error) => {
                    console.error('Autoplay blokiran ili greška:', error)
                    this.isPlaying = false
                })
        }
    },

    async fetchCurrentTrack() {
        try {
            const response = await fetch('/api/radio/current')
            if (response.ok) {
                this.currentTrack = await response.json()
            }
        } catch (error) {
            console.error('Greška pri dohvaćanju trenutne pjesme:', error)
        }
    },
})
