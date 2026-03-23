<?php

return [
    'radio_project_root' => env('RADIO_PROJECT_ROOT'),
    'icecast_host' => env('ICECAST_HOST', '127.0.0.1'),
    'icecast_port' => env('ICECAST_PORT', 8000),
    'icecast_telnet_port' => env('ICECAST_TELNET_PORT', 1234),
    'icecast_password' => env('ICECAST_PASSWORD', ''),
    'icecast_mount' => env('ICECAST_MOUNT', 'radio.mp3'),
    'icecast_stream_url' => env('ICECAST_STREAM_URL'),
];
