<?php

use ThirdRailPackages\ScheduleValidity\Date;
use ThirdRailPackages\ScheduleValidity\DaysRuns;
use ThirdRailPackages\ScheduleValidity\Indicator;
use ThirdRailPackages\ScheduleValidity\Schedule;
use ThirdRailPackages\ScheduleValidity\ScheduleValidator;
use ThirdRailPackages\ScheduleValidity\Uid;

include __DIR__ . '/../vendor/autoload.php';

function get_applicable_schedule(array $schedules, string $uid, string $date)
{
    return (new ScheduleValidator(...$schedules))->validate(
        Uid::fromString($uid),
        Date::fromString($date)
    );
}

$database_schedules = [
    [
        'uid'                 => 'A12345',
        'stp_indicator'       => 'P',
        'schedule_start_date' => '2020-08-03',
        'schedule_end_date'   => '2020-08-30',
        'running_days'        => '1111100',
        'toc'                 => 'ZZ'
    ], // Every weekday in august 2020
    [
        'uid'                 => 'A12345',
        'stp_indicator'       => 'C',
        'schedule_start_date' => '2020-08-03',
        'schedule_end_date'   => '2020-08-30',
        'running_days'        => '0000100'
    ] // 7, 14, 21, 28 Fridays in August 2020
];

$hydrated_schedules = array_map(function ($primative_schedule) {
    // You should create this class in YOUR software
    return new class($primative_schedule) implements Schedule
    {
        private $uid;
        private $stp_indicator;
        private $schedule_start_date;
        private $schedule_end_date;
        private $running_days;

        public function __construct(array $data)
        {
            $this->uid = Uid::fromString($data['uid']);
            $this->stp_indicator = Indicator::fromString($data['stp_indicator']);
            $this->schedule_start_date = Date::fromString($data['schedule_start_date']);
            $this->schedule_end_date = Date::fromString($data['schedule_end_date']);
            $this->running_days = DaysRuns::fromDaysRuns($data['running_days']);
        }

        public function uid(): Uid
        {
            return $this->uid;
        }

        public function indicator(): Indicator
        {
            return $this->stp_indicator;
        }

        public function startDate(): Date
        {
            return $this->schedule_start_date;
        }

        public function endDate(): Date
        {
            return $this->schedule_end_date;
        }

        public function daysRuns(): DaysRuns
        {
            return $this->running_days;
        }
    };
}, $database_schedules);

$result = get_applicable_schedule($hydrated_schedules, 'A12345', '2020-08-06');

echo sprintf(
    'UID: "%s", STP: "%s", Start Date: "%s"',
    $result->uid()->asString() ?? '',
    $result->indicator()->asString() ?? '',
    $result->startDate()->asString() ?? ''
) . PHP_EOL;
