<?php

function mpd_parse_status($status)
{
    $ret = array();

    // volume: -1
    // repeat: 0
    // random: 0
    // single: 0
    // consume: 0
    // playlist: 274
    // playlistlength: 44
    // xfade: 0
    // mixrampdb: 0.000000
    // mixrampdelay: nan
    // state: play
    // song: 1
    // songid: 228
    // time: 11:134
    // elapsed: 11.041
    // bitrate: 96
    // audio: 44100:24:2
    // nextsong: 2
    // nextsongid: 229
    // OK

    foreach (explode("\n", $status) as $line) {
        $a = preg_split('/:\s+/', $line);
        switch ($cmd = $a[0]) {
        case 'volume':
        case 'repeat':
        case 'playlist':
        case 'playlistlength':
        case 'xfade':
        case 'song':
        case 'songid':
        case 'bitrate':
        case 'nextsong':    // MPD 0.15
        case 'nextsongid':  // MPD 0.15
            $ret[$cmd] = intval($a[1]);
            break;
        case 'random':
        case 'single':      // MPD 0.15
        case 'consume':     // MPD 0.15
            $ret[$cmd] = boolval($a[1]);
            break;
        case 'mixrampdb':
        case 'mixrampdelay':
        case 'elapsed':     // MPD 0.16
        case 'duration':    // MPD 0.20
            $ret[$cmd] = floatval($a[1]);
            break;
        case 'time':
            $tmp = explode($a[1]);
            $ret[$cmd] = $tmp;
            break;
        case 'audio':
            $ret[$cmd] = array_combine(array('sampleRate', 'bits', 'channels'), explode(':', $a[1]));
            break;
        case 'OK':
            break;
        case 'state':
        default:
            $ret[$cmd] = $a[1];
            break;
        }
    }

    return $ret;
}

$s = 'volume: -1
repeat: 0
random: 0
single: 0
consume: 0
playlist: 274
playlistlength: 44
xfade: 0
mixrampdb: 0.000000
mixrampdelay: nan
state: play
song: 1
songid: 228
time: 11:134
elapsed: 11.041
bitrate: 96
audio: 44100:24:2
nextsong: 2
nextsongid: 229
OK';

var_dump(mpd_parse_status($s));
exit;

echo "$state $time_1:$time_2 ($elapsed)\n";

exit;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    exit;
}

$status = socket_connect($socket, '127.0.0.1', 6600);
if ($status === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    exit;
}

echo socket_read($socket, 4096);
socket_write($socket, "play\n");
echo socket_read($socket, 4096);
socket_close($socket);

