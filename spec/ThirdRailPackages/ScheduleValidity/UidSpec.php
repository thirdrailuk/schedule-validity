<?php

namespace spec\ThirdRailPackages\ScheduleValidity;

use PhpSpec\ObjectBehavior;
use ThirdRailPackages\ScheduleValidity\Uid;

class UidSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Uid::class);
    }

    function it_can_be_represented_as_string()
    {
        $this->beConstructedFromString('A12345');
        $this->asString()->shouldBe('A12345');
    }

    function it_can_be_created_from_vstp_uid()
    {
        $this->beConstructedFromString(' 43876');
        $this->asString()->shouldBe(' 43876');
    }

    function it_cannot_be_created_from_an_invalid_format()
    {
        $this->beConstructedFromString(' 12345 ');
        $this->shouldThrow(\Exception::class)->duringInstantiation();
    }
}
