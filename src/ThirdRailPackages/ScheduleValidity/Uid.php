<?php

namespace ThirdRailPackages\ScheduleValidity;

final class Uid
{
    /**
     * @var string
     */
    private $uid;

    private function __construct(string $uid)
    {
        $this->validateUid($uid);

        $this->uid = $uid;
    }

    public static function fromString(string $uid): self
    {
        return new self($uid);
    }

    public function asString(): string
    {
        return $this->uid;
    }

    private function validateUid(string $uid): void
    {
        if (!preg_match("/^([A-Z]| )?\d{5}$/", $uid)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Schedule UID "%s" is not a valid format',
                    $uid
                )
            );
        }
    }
}
