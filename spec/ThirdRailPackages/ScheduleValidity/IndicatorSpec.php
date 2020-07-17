<?php

namespace spec\ThirdRailPackages\ScheduleValidity;

use PhpSpec\ObjectBehavior;
use ThirdRailPackages\ScheduleValidity\Indicator;

class IndicatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Indicator::class);
    }

    function it_can_be_represented_as_wtt()
    {
        $this->beConstructedFromString('P');
        $this->asString()->shouldBe('P');
    }

    function it_can_be_represented_as_var()
    {
        $this->beConstructedFromString('O');
        $this->asString()->shouldBe('O');
    }

   function it_can_be_represented_as_can()
    {
        $this->beConstructedFromString('C');
        $this->asString()->shouldBe('C');
    }

    function it_can_be_represented_as_new()
    {
        $this->beConstructedFromString('N');
        $this->asString()->shouldBe('N');
    }

    function it_cannot_be_represented_as_invalid_indicator()
    {
        $this->beConstructedFromString('X');
        $this->shouldThrow(\Exception::class)->duringInstantiation();
    }
}
