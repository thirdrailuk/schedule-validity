<?php

namespace ThirdRailPackages\ScheduleValidity;

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
     * @return mixed
     */
    public function validate(Uid $uid, Date $date)
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
        /** @psalm-suppress MissingClosureReturnType */
        return $this->filter(function (Schedule $schedule) use ($applicableDate) {
            $scheduleDays = $schedule->daysRuns();
            $applicableDay = strtolower($applicableDate->asDate()->format('l'));

            return ($scheduleDays->$applicableDay());
        });
    }

    /**
     * @return self
     */
    public function rejectLTPSchedules()
    {
        return $this->reject(function (Schedule $schedule) {
            return $schedule->indicator()->asString() !== 'N';
        });
    }

    /**
     * @return static
     */
    public function sortBySTPIndicator()
    {
        $data = [];

        $this->each(function (Schedule $schedule, int $index) use (&$data) {
            /** @psalm-suppress all */
            $data[$index] = $schedule->indicator()->asString();
        });

        /** @psalm-suppress all */
        uasort($data, function ($a, $b) {
            return ($a > $b);
        });

        $results = [];

        /**
         * @psalm-suppress all
         */
        foreach (array_keys($data) as $key) {
            /** @psalm-suppress MixedAssignment */
            $results[] = $this->items[$key];
        }

        return new static($results); // @phpstan-ignore-line
    }
}
