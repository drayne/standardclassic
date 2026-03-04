<?php

namespace App\Http\Services;

class RadioService
{
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
