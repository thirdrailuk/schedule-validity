<?php

namespace spec\ThirdRailPackages\ScheduleValidity;

use PhpSpec\ObjectBehavior;
use ThirdRailPackages\ScheduleValidity\DaysRuns;

class DaysRunsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DaysRuns::class);
    }

    function it_can_display_running_days()
    {
        $this->beConstructedFromDaysRuns('1111100');
        $this->monday()->shouldBe(true);
        $this->tuesday()->shouldBe(true);
        $this->wednesday()->shouldBe(true);
        $this->thursday()->shouldBe(true);
        $this->friday()->shouldBe(true);
        $this->saturday()->shouldBe(false);
        $this->sunday()->shouldBe(false);
    }

    function it_can_register_exception()
    {
        $this->beConstructedFromDaysRuns('00');
        $this->shouldThrow(\Exception::class)
            ->duringInstantiation();
    }
}
