<?php

namespace ThirdRailPackages\ScheduleValidity;

/**
 * @template TKey of array-key
 *
 * @extends Collection<TKey, Schedule>
 */
class ScheduleCollection extends Collection
{
    public function add(Schedule $schedule): self
    {
        $this->items[] = $schedule;

        return $this;
    }

    /**
     * @param Uid  $uid
     * @param Date $date
     *
     * @return ?Schedule
     */
    public function validate(Uid $uid, Date $date)
    {
        return $this
            ->filterByUid($uid)
            ->filterByApplicableDate($date)
            ->filterByRunningDay($date)
            ->sortBySTPIndicator()
            ->first();
    }

    /**
     * @param Uid $uid
     *
     * @return self
     */
    public function filterByUid(Uid $uid)
    {
        return $this->filter(function (Schedule $schedule) use ($uid) {
            return $schedule->uid()->asString() === $uid->asString();
        });
    }

    /**
     * @param Date $applicableDate
     *
     * @return self
     */
    public function filterByApplicableDate(Date $applicableDate)
    {
        return $this->filter(function (Schedule $schedule) use ($applicableDate) {
            $startDate = $schedule->startDate();
            $endDate = $schedule->endDate();

             return (
                $applicableDate->asTimestamp() >= $startDate->asTimestamp() &&
                $applicableDate->asTimestamp() <= $endDate->asTimestamp()
            );
        });
    }

    /**
     * @param Date $applicableDate
     *
     * @return self
     */
    public function filterByRunningDay(Date $applicableDate)
    {
        return $this->filter(function (Schedule $schedule) use ($applicableDate) {
            $scheduleDays = $schedule->daysRuns();
            $applicableDay = strtolower($applicableDate->asDate()->format('l'));

            return ($scheduleDays->$applicableDay());
        });
    }

    public function sortBySTPIndicator(): self
    {
        return $this->sort(function (Schedule $a, Schedule $b) {
            return (int)($a->indicator()->asString() > $b->indicator()->asString());
        })->values();
    }
}
