<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use ThirdRailPackages\ScheduleValidity\Date;
use ThirdRailPackages\ScheduleValidity\DaysRuns;
use ThirdRailPackages\ScheduleValidity\Indicator;
use ThirdRailPackages\ScheduleValidity\ScheduleCollection;
use ThirdRailPackages\ScheduleValidity\ScheduleValidator;
use ThirdRailPackages\ScheduleValidity\Uid;

/**
 * Defines application features from the specific context.
 */
class ScheduleValidityContext implements Context
{
    /**
     * @var ScheduleValidator
     */
    private $validator;
    /**
     * @var \ThirdRailPackages\ScheduleValidity\Schedule|null
     */
    private $foundSchedule;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the following schedules exist
     */
    public function theFollowingSchedulesExist(TableNode $table)
    {
        $items = [];

        foreach ($table->getHash() as $hash) {
            $items[] = new \Fake\Schedule(
                Uid::fromString($hash['uid']),
                Indicator::fromString($hash['indicator']),
                Date::fromString($hash['start_date']),
                Date::fromString($hash['end_date']),
                DaysRuns::fromDaysRuns($hash['days_runs'])
            );
        }

        $this->validator = new ScheduleValidator(...$items);
    }

    /**
     * @When Uid :uid is selected on :date
     */
    public function uidIsSelectedOn(Uid $uid, Date $date)
    {
        /** @var \ThirdRailPackages\ScheduleValidity\Schedule $foundSchedule */
        $this->foundSchedule = $this->validator->validate($uid, $date);
    }

    /**
     * @Then Schedule :uid indicator :indicator starting on :date should be applied
     */
    public function scheduleIndicatorStartingOnShouldBeApplied(
        Uid $uid,
        Indicator $indicator,
        Date $date
    ) {

        \Webmozart\Assert\Assert::eq(
            $this->foundSchedule->uid()->asString(),
            $uid->asString()
        );

        \Webmozart\Assert\Assert::eq(
            $this->foundSchedule->indicator()->asString(),
            $indicator->asString()
        );

        \Webmozart\Assert\Assert::eq(
            $this->foundSchedule->startDate()->asString(),
            $date->asString()
        );
    }

    /**
     * @Transform :uid
     */
    public function transformUid(string $uid): Uid
    {
        return Uid::fromString($uid);
    }

    /**
     * @Transform :indicator
     */
    public function transformIndicator(string $indicator): Indicator
    {
        return Indicator::fromString($indicator);
    }

    /**
     * @Transform :daysRuns
     */
    public function transformDaysRuns(string $daysRuns): DaysRuns
    {
        return DaysRuns::fromDaysRuns($daysRuns);
    }

    /**
     * @Transform :date
     */
    public function transformDate(string $date): Date
    {
        return Date::fromString($date);
    }
}
