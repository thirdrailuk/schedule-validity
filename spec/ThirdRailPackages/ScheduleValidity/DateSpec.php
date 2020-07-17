<?php

namespace spec\ThirdRailPackages\ScheduleValidity;

use PhpSpec\ObjectBehavior;
use ThirdRailPackages\ScheduleValidity\Date;

class DateSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedFromString('2020-07-16');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Date::class);
    }

    function it_can_be_represented_as_string()
    {
        $this->asString()->shouldBe('2020-07-16');
    }
}
