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
        $this->collection = new ScheduleCollection($schedules);
    }

    public function validate(Uid $uid, Date $date): ?Schedule
    {
        return $this->collection->validate($uid, $date);
    }
}
