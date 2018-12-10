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

class Guard
{
    /**
     * @var int
     */
    private $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function fellAsleepAt(DateTimeImmutable $timestamp)
    {
        // TODO: Store when this guard fell asleep
        var_dump($timestamp);
    }
}