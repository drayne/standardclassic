<?php

namespace App\Http\Services;

use App\Models\Stat;
use Illuminate\Support\Facades\Http;

class RadioService
{
    public function getListenersCount(): int
    {
        try {
            $host = config('radio.icecast_host');
            $port = config('radio.icecast_port');
            $response = Http::timeout(2)->get(sprintf('http://%s:%s/status-json.xsl', $host, $port));

            if ($response->successful()) {
                $data = $response->json();
                $sources = $data['icestats']['source'] ?? null;

                $currentListeners = 0;
                if ($sources) {
                    if (isset($sources['listeners'])) {
                        $currentListeners = $sources['listeners'];
                    } else if (is_array($sources)) {
                        foreach ($sources as $source) {
                            $currentListeners += $source['listeners'] ?? 0;
                        }
                    }
                }

                return $currentListeners;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Icecast listener fetch failed: " . $e->getMessage());
        }

        return 0;
    }

    public function updateStatistics(): Stat
    {
        $currentListeners = $this->getListenersCount();
        $today = now()->format('Y-m-d');

        $stat = Stat::firstOrCreate(
            ['date' => $today],
            ['current' => 0, 'highest' => 0]
        );

        $stat->current = $currentListeners;

        if ($currentListeners > $stat->highest) {
            $stat->highest = $currentListeners;
        }

        $stat->save();

        return $stat;
    }

    public function getTodayStats(): ?Stat
    {
        return Stat::where('date', now()->format('Y-m-d'))->first();
    }

    public function skipTrack(): bool
    {
        try {
            // Koristimo host.docker.internal jer Liquidsoap radi na WSL hostu, a ne u kontejneru
            $host = config('radio.icecast_host');
            $port = config('radio.icecast_telnet_port');

            $fp = @fsockopen($host, $port, $errno, $errstr, 2);

            if (!$fp) {
                throw new \Exception("Nije moguće uspostaviti vezu: $errstr ($errno)");
            }

            // Slanje komande
            fwrite($fp, "radio_mp3.skip\n");
            fwrite($fp, "quit\n");
            fclose($fp);

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Radio skip failed: " . $e->getMessage());
            return false;
        }
    }
}
