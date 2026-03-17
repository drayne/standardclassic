import { reactive } from 'vue'

export const radioStore = reactive({
    isPlaying: false,
    isMuted: false,
    volume: localStorage.getItem('radioVolume')
        ? parseFloat(localStorage.getItem('radioVolume'))
        : 0.7,
    previousVolume: 0.7,
    audio: null,
    streamUrl: null,
    currentTrack: {
        title: 'Učitavanje...',
        artist: '',
    },

    init(streamUrl) {
        this.streamUrl = streamUrl
        if (!this.audio) {
            this.audio = new Audio()
            this.audio.preload = 'none'
            this.audio.volume = this.volume
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
            this.updateVolume(
                this.previousVolume > 0 ? this.previousVolume : 0.7,
            )
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
                })
                .catch((error) => {
                    console.error('Greška pri pokretanju radija:', error)
                })
            this.isPlaying = true
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
