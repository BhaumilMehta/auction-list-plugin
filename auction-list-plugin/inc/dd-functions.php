<?php 

function format_time_remaining($end_time) {
  
    $current_time = time();

    $remaining_seconds = $end_time - $current_time;

    if ($remaining_seconds <= 0) {
        return "0D, 0H, 0M";
    }

    $days = floor($remaining_seconds / 86400);
    $remaining_seconds %= 86400;

    $hours = floor($remaining_seconds / 3600);
    $remaining_seconds %= 3600;

    $minutes = floor($remaining_seconds / 60);


    return "{$days}D, {$hours}H, {$minutes}M";
}

date_default_timezone_set('America/Denver'); // Set timezone to MST

function getTimeRemaining($end_date_text) {
    // Convert the string to a DateTime object
    $targetTime = DateTime::createFromFormat('l, m/d/y @ g:i A T', $end_date_text);
    
    if (!$targetTime) {
        return "Invalid date format";
    }

    // Get the current time
    $currentTime = new DateTime();

    // Calculate the difference
    $interval = $currentTime->diff($targetTime);

    // Format the remaining time
    return sprintf(
        "Time Remaining: %dD, %dH, %dM, %dS",
        $interval->d,
        $interval->h,
        $interval->i,
        $interval->s
    );
}

function hexToRgb($hex) {
    $hex = str_replace("#", "", $hex);
    if(strlen($hex) == 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return "$r, $g, $b";
}

