<?php

namespace ThirdRailPackages\ScheduleValidity;

/**
 * @template TKey of array-key
 * @template TSchedule of Schedule
 *
 * @extends Collection<TKey, TSchedule>
 */
class ScheduleCollection extends Collection
{
    public function validate(Uid $uid, Date $date): ?Schedule
    {
         $collection = $this
             ->filterByUid($uid)
             ->filterByApplicableDate($date)
             ->filterByRunningDay($date)
             ->sortBySTPIndicator();

        $stp = $collection->rejectLTPSchedules();

        if ($stp->count() > 0) {
            return $stp->first();
        }

         return $collection->first();
    }

    /**
     * @param Uid $uid
     *
     * @return ScheduleCollection<(int|string), Schedule>
     */
    public function filterByUid(Uid $uid)
    {
        return new static(
            $this->filter(function (Schedule $schedule) use ($uid) {
                return $schedule->uid()->asString() === $uid->asString();
            })->toArray()
        );
    }

    /**
     * @param Date $applicableDate
     *
     * @return ScheduleCollection<(int|string), Schedule>
     */
    public function filterByApplicableDate(Date $applicableDate)
    {
        return new static(
            $this->filter(function (Schedule $schedule) use ($applicableDate) {
                $startDate = $schedule->startDate();
                $endDate = $schedule->endDate();

                return $applicableDate->asTimestamp() >= $startDate->asTimestamp()
                    && $applicableDate->asTimestamp() <= $endDate->asTimestamp();
            })->toArray()
        );
    }

    /**
     * @param Date $applicableDate
     *
     * @return ScheduleCollection<(int|string), Schedule>
     */
    public function filterByRunningDay(Date $applicableDate)
    {
        return new static(
            $this->filter(function (Schedule $schedule) use ($applicableDate) {
                $scheduleDays = $schedule->daysRuns();
                $applicableDay = strtolower($applicableDate->asDate()->format('l'));

                return $scheduleDays->$applicableDay();
            })->toArray()
        );
    }

    /**
     * @return ScheduleCollection<(int|string), Schedule>
     */
    public function rejectLTPSchedules()
    {
        return new static(
            $this->reject(function (Schedule $schedule) {
                return $schedule->indicator()->asString() !== 'N';
            })->toArray()
        );
    }

    /**
     * @return ScheduleCollection<(int|string), Schedule>
     */
    public function sortBySTPIndicator()
    {
        return new static(
            $this->sort(function (Schedule $a, Schedule $b) {
                $aStpIndicator = $a->indicator()->asString();
                $bStpIndicator = $b->indicator()->asString();
                if ($aStpIndicator === $bStpIndicator) {
                    return 0;
                }

                return ($aStpIndicator < $bStpIndicator) ? -1 : 1;
            })->toArray()
        );
    }
}
