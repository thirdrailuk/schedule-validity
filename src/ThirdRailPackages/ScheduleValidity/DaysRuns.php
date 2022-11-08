<?php

namespace ThirdRailPackages\ScheduleValidity;

use InvalidArgumentException;

final class DaysRuns
{
    /**
     * @var bool
     */
    private $monday;

    /**
     * @var bool
     */
    private $tuesday;

    /**
     * @var bool
     */
    private $wednesday;

    /**
     * @var bool
     */
    private $thursday;

    /**
     * @var bool
     */
    private $friday;

    /**
     * @var bool
     */
    private $saturday;

    /**
     * @var bool
     */
    private $sunday;

    public static function fromDaysRuns(string $daysRuns): self
    {
        if (strlen($daysRuns) !== 7) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid Days Runs length "%s" passed',
                    $daysRuns
                )
            );
        }

        return new self(...array_map(
            function ($item) {
                return (bool) $item;
            },
            str_split($daysRuns)
        ));
    }

    private function __construct(
        bool $monday,
        bool $tuesday,
        bool $wednesday,
        bool $thursday,
        bool $friday,
        bool $saturday,
        bool $sunday
    ) {
        $this->monday    = $monday;
        $this->tuesday   = $tuesday;
        $this->wednesday = $wednesday;
        $this->thursday  = $thursday;
        $this->friday    = $friday;
        $this->saturday  = $saturday;
        $this->sunday    = $sunday;
    }

    public function monday(): bool
    {
        return $this->monday;
    }

    public function tuesday(): bool
    {
        return $this->tuesday;
    }

    public function wednesday(): bool
    {
        return $this->wednesday;
    }

    public function thursday(): bool
    {
        return $this->thursday;
    }

    public function friday(): bool
    {
        return $this->friday;
    }

    public function saturday(): bool
    {
        return $this->saturday;
    }

    public function sunday(): bool
    {
        return $this->sunday;
    }
}
