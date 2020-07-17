<?php

namespace Fake;

use ThirdRailPackages\ScheduleValidity\Date;
use ThirdRailPackages\ScheduleValidity\DaysRuns;
use ThirdRailPackages\ScheduleValidity\Indicator;
use ThirdRailPackages\ScheduleValidity\Uid;

class Schedule implements \ThirdRailPackages\ScheduleValidity\Schedule
{
    /**
     * @var Uid
     */
    private $uid;
    /**
     * @var Indicator
     */
    private $indicator;
    /**
     * @var Date
     */
    private $startDate;
    /**
     * @var Date
     */
    private $endDate;
    /**
     * @var DaysRuns
     */
    private $daysRuns;

    public function __construct(
        Uid $uid,
        Indicator $indicator,
        Date $startDate,
        Date $endDate,
        DaysRuns $daysRuns
    ) {
        $this->uid = $uid;
        $this->indicator = $indicator;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->daysRuns = $daysRuns;
    }

    public function uid(): Uid
    {
        return $this->uid;
    }

    public function indicator(): Indicator
    {
        return $this->indicator;
    }

    public function startDate(): Date
    {
        return $this->startDate;
    }

    public function endDate(): Date
    {
        return $this->endDate;
    }

    public function daysRuns(): DaysRuns
    {
        return $this->daysRuns;
    }
}
