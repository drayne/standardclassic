import { reactive } from 'vue'

export const radioStore = reactive({
    isPlaying: false,
    isLoading: false,
    isFetching: false,
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

            // Kada audio zapravo počne da se emituje (baferovanje završeno)
            this.audio.addEventListener('playing', () => {
                this.isLoading = false
            })

            // Alternativni događaj ako 'playing' kasni
            this.audio.addEventListener('canplay', () => {
                if (this.isLoading && this.isPlaying) {
                    this.isLoading = false
                }
            })

            // Sinhronizacija sa sistemskim kontrolama (tastatura, bluetooth, itd.)
            this.audio.addEventListener('pause', () => {
                // Ako je audio pauziran van togglePlay funkcije (npr. tastaturom)
                if (this.isPlaying) {
                    this.isPlaying = false
                }
                this.updateMediaSession()
            })

            this.audio.addEventListener('play', () => {
                // Ako je audio pokrenut van togglePlay funkcije
                if (!this.isPlaying) {
                    this.isPlaying = true
                }
                this.updateMediaSession()
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
        this.updateMediaSession()
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
            this.isLoading = false
            this.updateMediaSession()
        } else {
            this.isLoading = true
            this.isPlaying = true // Odmah mijenjamo stanje da UI reaguje
            this.audio.src = this.streamUrl
            this.audio
                .play()
                .then(() => {
                    this.audio.volume = this.volume
                    this.updateMediaSession()
                })
                .catch((error) => {
                    console.error('Autoplay blokiran ili greška:', error)
                    this.isPlaying = false
                    this.isLoading = false
                })
        }
    },

    async fetchCurrentTrack() {
        if (this.isFetching) return

        this.isFetching = true
        try {
            const response = await fetch('/api/radio/current')
            if (response.ok) {
                this.currentTrack = await response.json()
                this.updateMediaSession()
            }
        } catch (error) {
            console.error('Greška pri pribavljanju informacija o trenutnoj pjesmi:', error)
        } finally {
            this.isFetching = false
        }
    },

    updateMediaSession() {
        if ('mediaSession' in navigator) {
            const track = this.currentTrack
            const title = track.title || 'Uživo'
            const artist = track.composer_name || track.artist || 'StandardClassic Radio'
            const album = 'StandardClassic Radio'
            const logoUrl = window.location.origin + '/images/logo.png'

            navigator.mediaSession.metadata = new MediaMetadata({
                title: title,
                artist: artist,
                album: album,
                artwork: [
                    { src: logoUrl, sizes: '96x96', type: 'image/png' },
                    { src: logoUrl, sizes: '128x128', type: 'image/png' },
                    { src: logoUrl, sizes: '192x192', type: 'image/png' },
                    { src: logoUrl, sizes: '256x256', type: 'image/png' },
                    { src: logoUrl, sizes: '384x384', type: 'image/png' },
                    { src: logoUrl, sizes: '512x512', type: 'image/png' },
                ],
            })

            // Ažuriranje stanja reprodukcije za sistem
            navigator.mediaSession.playbackState = this.isPlaying ? 'playing' : 'paused'

            // Postavljanje kontrola
            navigator.mediaSession.setActionHandler('play', () => this.togglePlay())
            navigator.mediaSession.setActionHandler('pause', () => this.togglePlay())
            navigator.mediaSession.setActionHandler('stop', () => {
                this.audio.pause()
                this.audio.src = ''
                this.audio.load()
                this.isPlaying = false
                this.isLoading = false
                this.updateMediaSession()
            })
        }
    },
})
