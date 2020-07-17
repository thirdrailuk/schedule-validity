<?php

namespace ThirdRailPackages\ScheduleValidity;

class ScheduleValidator
{
    /**
     * @var ScheduleCollection
     */
    private $collection;

    public function __construct(Schedule ...$schedules)
    {
        $this->collection = ScheduleCollection::make($schedules);
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     *
     * @param Uid  $uid
     * @param Date $date
     *
     * @return Schedule|null
     *
     */
    public function validate(Uid $uid, Date $date): ?Schedule
    {
        /** @psalm-suppress MixedReturnStatement */
        return $this->collection->validate($uid, $date);
    }
}
