<?php

namespace ThirdRailPackages\ScheduleValidity;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;

final class Date
{
    /**
     * @var DateTimeInterface
     */
    private $date;

    public static function fromString(string $date): self
    {
        return new self(
            self::validate($date)
        );
    }

    private function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function asDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function asTimestamp(): int
    {
        return $this->date->getTimestamp();
    }

    public function asString(): string
    {
        return $this->date->format('Y-m-d');
    }

    private static function validate(string $date): DateTimeInterface
    {
        $datetime = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $date,
            new DateTimeZone('Europe/London')
        );

        if (!$datetime instanceof DateTimeInterface) {
            throw new Exception(
                sprintf(
                    '"%s" does not create %s',
                    $date,
                    DateTimeInterface::class
                )
            );
        }

        return $datetime;
    }
}
