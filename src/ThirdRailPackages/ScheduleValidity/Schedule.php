<?php

namespace ThirdRailPackages\ScheduleValidity;

interface Schedule
{
    public function uid(): Uid;

    public function indicator(): Indicator;

    public function startDate(): Date;

    public function endDate(): Date;

    public function daysRuns(): DaysRuns;
}
