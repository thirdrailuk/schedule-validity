<?php

namespace ThirdRailPackages\ScheduleValidity;

use InvalidArgumentException;

final class Indicator
{
    /**
     * @var string
     */
    private $indicator;

    public static function fromString(string $indicator): self
    {
        return new self($indicator);
    }

    private function __construct(string $indicator)
    {
        $this->validate($indicator);

        $this->indicator = $indicator;
    }

    public function asString(): string
    {
        return $this->indicator;
    }

    private function validate(string $indicator): void
    {
        if (!in_array($indicator, ['C', 'O', 'N', 'P'], true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Indicator "%s" is not valid',
                    $indicator
                )
            );
        }
    }
}
