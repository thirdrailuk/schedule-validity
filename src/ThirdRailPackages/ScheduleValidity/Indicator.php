<?php

namespace ThirdRailPackages\ScheduleValidity;

final class Indicator
{
    /**
     * @var string
     */
    private $indicator;

    private function __construct(string $indicator)
    {
        $this->validate($indicator);

        $this->indicator = $indicator;
    }

    public static function fromString(string $indicator): self
    {
        return new self($indicator);
    }

    public function asString(): string
    {
        return $this->indicator;
    }

    private function validate(string $indicator): void
    {
        if (!in_array($indicator, ['C', 'O', 'N', 'P'])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Indicator "%s" is not valid',
                    $indicator
                )
            );
        }
    }
}
