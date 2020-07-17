<?php

namespace spec\ThirdRailPackages\ScheduleValidity;

use PhpSpec\ObjectBehavior;
use ThirdRailPackages\ScheduleValidity\Collection;
use ThirdRailPackages\ScheduleValidity\Date;
use ThirdRailPackages\ScheduleValidity\DaysRuns;
use ThirdRailPackages\ScheduleValidity\Indicator;
use ThirdRailPackages\ScheduleValidity\Schedule;
use ThirdRailPackages\ScheduleValidity\ScheduleCollection;
use ThirdRailPackages\ScheduleValidity\Uid;

class ScheduleCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ScheduleCollection::class);
        $this->shouldHaveType(Collection::class);
    }

    function it_can_add_schedules(
        Schedule $schedule,
        Schedule $schedule1,
        Schedule $schedule2
    ) {
        $this
            ->add($schedule)
            ->add($schedule1)
            ->add($schedule2)
            ->all()
            ->shouldBeLike([
                $schedule,
                $schedule1,
                $schedule2
            ]);
    }

    function it_can_filter_by_uid()
    {
        $expected = $this->create_schedule('A12345', 'P', '2013-01-07', '2013-01-11', '1111100');

        $this->add($expected);

        $this->add(
            $this->create_schedule('A54321', 'P', '2013-01-07', '2013-01-11', '1111100')
        );

        $this->filterByUid(
            Uid::fromString('A12345')
        );

//        ->shouldBeLike(
//            Collection::make($expected)
//        );
    }

    function it_can_filter_by_schedule_dates()
    {
        $expected = $this->create_schedule('A12345', 'P', '2013-01-07', '2013-01-08', '1111100');

        $this->beConstructedWith([
            $expected,
            $this->create_schedule('A54321', 'P', '2013-01-09', '2013-01-11', '1111100')
        ]);

        $this
            ->filterByApplicableDate(Date::fromString('2013-01-08'))
            ->shouldHaveCount(1);
    }

    function it_can_filter_by_running_day()
    {
        $expected = $this->create_schedule('A12345', 'P', '2013-01-07', '2013-01-08', '1100000');

        $this->beConstructedWith([
            $expected,
            $this->create_schedule('A54321', 'P', '2013-01-13', '2013-01-13', '0010000')
        ]);

        $this->filterByRunningDay(Date::fromString('2013-01-07'))
            ->shouldHaveCount(1);
    }

    function it_can_sort_stp_indicators()
    {
        $wtt = $this->create_schedule('A54321', 'P', '2013-01-13', '2013-01-13', '0010000');
        $can = $this->create_schedule('A54321', 'C', '2013-01-13', '2013-01-13', '0010000');
        $var = $this->create_schedule('A54321', 'O', '2013-01-13', '2013-01-13', '0010000');

        $this->beConstructedWith([$wtt, $can, $var]);

        $this->sortBySTPIndicator()
            ->toArray()
            ->shouldBe([$can, $var, $wtt]);
    }

    function it_can_find_a_schedule()
    {
        $data = [
            $wtt = $this->create_schedule('A54321', 'P', '2013-01-13', '2013-01-13', '0010000'),
            $can = $this->create_schedule('A54321', 'C', '2013-01-13', '2013-01-13', '0010000'),
            $var = $this->create_schedule('A54321', 'O', '2013-01-13', '2013-01-13', '0010000'),
            $valid = $this->create_schedule('A12345', 'P', '2020-06-29', '2020-07-09', '1111100')
        ];

        $this->beConstructedWith($data);

        $this->validate(
            Uid::fromString('A12345'),
            Date::fromString('2020-07-01')
        )->shouldBe(
            $valid
        );
    }

    function it_can_handle_not_finding_a_schedule()
    {
        $data = [
            $wtt = $this->create_schedule('A54321', 'P', '2013-01-13', '2013-01-13', '0010000'),
            $can = $this->create_schedule('A54321', 'C', '2013-01-13', '2013-01-13', '0010000'),
            $var = $this->create_schedule('A54321', 'O', '2013-01-13', '2013-01-13', '0010000'),
        ];

        $this->beConstructedWith($data);

        $this->validate(
            Uid::fromString('A12345'),
            Date::fromString('2020-07-01')
        )->shouldBeNull();
    }

    private function create_schedule(
        string $uid,
        string $indicator,
        string $startDate,
        string $endDate,
        string $daysRuns
    ): Schedule {
        return new \Fake\Schedule(
            Uid::fromString($uid),
            Indicator::fromString($indicator),
            Date::fromString($startDate),
            Date::fromString($endDate),
            DaysRuns::fromDaysRuns($daysRuns)
        );
    }
}
