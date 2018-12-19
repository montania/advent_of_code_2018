<?php

class Event
{
    private $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function isBeginsShiftEvent(): bool
    {
        return strpos($this->data, 'begins shift') !== false;
    }

    public function isFallsAsleepEvent(): bool
    {
        return strpos($this->data, 'falls asleep') !== false;
    }

    public function isWokeUpEvent(): bool
    {
        return strpos($this->data, 'wakes up') !== false;
    }

    public function getGuardNumber(): ?int
    {
        if ($this->isBeginsShiftEvent()) {
            // string(43) "[1518-02-05 00:00] Guard #3109 begins shift"

            // Return number after # character
            preg_match('/#(\d+)/', $this->data, $matches);

            return intval($matches[1]);
        }

        // No guard number found
        return null;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        // Match anything between [ and ]
        preg_match('/\[([^]]+)\]/', $this->data, $matches);

        return new \DateTimeImmutable($matches[1]);
    }
}

class AsleepPeriod
{
    /**
     * @var DateTimeImmutable
     */
    private $from;
    /**
     * @var DateTimeImmutable
     */
    private $to;

    public function __construct(DateTimeImmutable $from, DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function getTotalSleepInMinutes(): int
    {
        return abs($this->from->getTimestamp() - $this->to->getTimestamp()) / 60;
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function getSleepingMinutes(): array
    {
        $current   = new DateTime($this->from->format('c'));
        $minutes   = [];
        $oneMinute = new DateInterval('PT1M');


        while ($current <= $this->to) {
            $minutes[] = $current->format('i');
            $current->add($oneMinute);
        }

        return $minutes;
    }
}

class Guard
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var ?DateTimeImmutable
     */
    private $fellAsleepAt;

    /**
     * @var AsleepPeriod[]
     */
    private $sleptAt = [];

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function fellAsleepAt(DateTimeImmutable $timestamp)
    {
        if (!is_null($this->fellAsleepAt)) {
            throw new \Exception('Guard is already asleep');
        }

        // Temporary store when guard fell asleep
        $this->fellAsleepAt = $timestamp;
    }

    public function wokeUpAt(DateTimeImmutable $timestamp)
    {
        if (is_null($this->fellAsleepAt)) {
            throw new \Exception("I don't know when this guard fell asleep");
        }

        // Store sleeping period
        $this->sleptAt[] = new AsleepPeriod($this->fellAsleepAt, $timestamp);

        $this->fellAsleepAt = null;
    }

    public function getTotalMinutesAsleep()
    {
        return array_sum(
            array_map(
                function (AsleepPeriod $period) {
                    return $period->getTotalSleepInMinutes();
                },
                $this->sleptAt
            )
        );
    }

    /**
     * @throws Exception
     */
    public function getMinuteMostAsleep()
    {
        $minutes = [];

        foreach ($this->sleptAt as $period) {
            foreach ($period->getSleepingMinutes() as $minute) {
                $minutes[$minute]++;
            }
        }

        // Find out which minute the guard is most asleep
        var_dump($minutes);
    }
}