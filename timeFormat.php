<?php
$time = strtotime($data['time_in']);
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

$format_tanggal = $hari[date('w', $time)] . " " . date('H:i', $time) . " / " . date('d', $time) . " " . $bulan[date('n', $time) - 1] . " " . date('Y', $time);
