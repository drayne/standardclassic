<?php

namespace App\Http\Services;

class RadioService
{
    public function skipTrack(): bool
    {
        try {
            // Koristimo host.docker.internal jer Liquidsoap radi na WSL hostu, a ne u kontejneru
            $host = "host.docker.internal";
            $port = 1234;

            $fp = @fsockopen($host, $port, $errno, $errstr, 2);

            if (!$fp) {
                // Ako host.docker.internal ne prođe, probajmo IP adresu gateway-a (često 172.17.0.1)
                $host = "172.17.0.1";
                $fp = @fsockopen($host, $port, $errno, $errstr, 2);
            }

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
