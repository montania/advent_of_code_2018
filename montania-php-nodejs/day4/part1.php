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

    elseif ($event->isWokeUpEvent()) {
        $guard->wokeUpAt($event->getTimestamp());
    }

    else {
        throw new \Exception('Unknown log event');
    }
}

// Strategy 1: Find the guard that has the most minutes asleep. What minute does that guard spend asleep the most?

// Order guards with the guard with the most minutes asleep first
usort($guards, function (Guard $a, Guard $b) {
    return $b->getTotalMinutesAsleep() - $a->getTotalMinutesAsleep();
});

var_dump($guards[0], $guards[0]->getTotalMinutesAsleep(), $guards[0]->getMinuteMostAsleep());