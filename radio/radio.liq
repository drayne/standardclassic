settings.log.level.set(2)
settings.log.stdout.set(true)
settings.server.telnet.set(true)
settings.server.telnet.bind_addr.set("0.0.0.0")
settings.server.telnet.port.set(1234)

# --- 0. ZA REPORT PUSTENE PJESME ---
def on_track_started(m) =
  media_id = m["media_id"]
  type = m["type"]
  # Asinhroni HTTP poziv Laravelu
  thread.run({
    ignore(http.get("https://test.standardclassic.ba/api/radio/report-played?media_id=#{media_id}&type=#{type}"))
  })
end

# --- 1. DEFINICIJA IZVORA ---

# Red za zakazane termine (ovde Laravel "gura" fajlove)
scheduled_queue = request.queue(id="scheduled_queue")

# Regularna muzika sa Laravela
def get_next_track() =
  raw_json = http.get("https://test.standardclassic.ba/api/radio/next")

  # 1. Izvlačimo media_id iz JSON-a
  data = json.parse(default=[("title","Unknown"), ("artist", "Unknown"), ("path", ""), ("media_id", "")], raw_json)

  title    = data["title"]
  artist   = data["artist"]
  path     = data["path"]
  media_id = data["media_id"]

  print("🎵 PLAYLIST: #{artist} - #{title}")

  # 2. FIX: Koristimo #{media_id} umesto #{id}
  request.create("annotate:title=\"#{title}\",artist=\"#{artist}\",media_id=\"#{media_id}\",type=\"regular\":#{path}")
end

music = request.dynamic(get_next_track)

# --- 2. LOGIKA ZA PROVERU RASPOREDA (Tajmer) ---
def on_scheduled_start(_) =
  print("🚀 Zakazana pesma krenula, preskačem trenutnu regularnu...")
  thread.run( { music.skip() } )
end

scheduled_queue.on_track(on_scheduled_start)
scheduled_queue.on_track(on_track_started)
music.on_track(on_track_started)

def check_for_scheduled_media() =
  raw_json = http.get("https://test.standardclassic.ba/api/radio/check-schedule")
  data = json.parse(default=[("status","idle"), ("path", ""), ("title", ""), ("artist", ""), ("media_id", "")], raw_json)

  if data["status"] == "play_now" then
    path     = data["path"]
    title    = data["title"]
    artist   = data["artist"]
    media_id = data["media_id"]

    print("🚀 ZAKAZANO: #{title}")

    # FIX: Dodajemo "req =" ispred request.create
    req = request.create("annotate:title=\"#{title}\",artist=\"#{artist}\",media_id=\"#{media_id}\",type=\"scheduled\":#{path}")

    # Sada varijabla 'req' postoji i možeš je gurnuti u queue
    scheduled_queue.push(req)
  end

  10.0
end

thread.run.recurrent(delay=10.0, check_for_scheduled_media)

# --- 3. MIKSANJE ---
# Definišemo funkciju koja proverava dostupnost reda
radio = switch(track_sensitive=false, [
  ({ scheduled_queue.is_ready() }, scheduled_queue),
  ({ true }, music)
])

# Crossfade i ostalo ostaje isto
radio = crossfade(smart=true, duration=3.0, fade_in=2.0, fade_out=2.0, radio)
radio = fallback(track_sensitive=false, [radio, blank()])

# --- 5. OUTPUT ---

output.icecast(
  %mp3(bitrate=128),
  host="localhost",
  port=8000,
  password="sc2026Radio-",
  mount="radio.mp3",
  radio
)
