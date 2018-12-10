<?php

require 'lib.php';

$data = file_get_contents('input');

// Parse log as lines and sort in chronological order
$log = explode(PHP_EOL, $data);
sort($log);

// Index of guards with their number as key
$guards = [];

/** @var Guard $guard */
$guard = null;

foreach ($log as $logEntry) {

    $event = new Event($logEntry);

    // Switch guard whenever one begins a shift
    if ($event->isBeginsShiftEvent()) {

        $guardNumber = $event->getGuardNumber();

        if (!isset($guards[$guardNumber])) {
            $guard = $guards[$guardNumber] = new Guard($guardNumber);
        } else {
            $guard = $guards[$guardNumber];
        }
    }

    elseif ($event->isFallsAsleepEvent()) {
        $guard->fellAsleepAt($event->getTimestamp());
    }

    else {
        // TODO: implement more events
        var_dump($logEntry); die();
    }
}