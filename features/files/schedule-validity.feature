Feature: Validate applicable UID for a given date

    Schedule and Association validities are between a start date and an end date on particular days of the week. They
    each have a Short Term Planning (STP) indicator field as follows:

    - C - Planned cancellation: the schedule does not apply on this date, and the train will not run. Typically seen on public holidays when an alternate schedule applies, or on Christmas Day.
    - N - STP schedule: similar to a permanent schedule, but planned through the Short Term Planning process and not capable of being overlaid
    - O - Overlay schedule: an alteration to a permanent schedule
    - P - Permanent schedule: a schedule planned through the Long Term Planning process

    Permanent ('P') schedules can be overlaid by another schedule with the same UID - either a Variation ('O') or
    Cancellation Variation ('C'). For any particular day, of all the schedules for that UID valid on that day,
    the 'C' or 'O' schedule is the one which applies. Conveniently, it also means that the lowest
    alphabetical STP indicator wins - 'C' and 'O' are both lower in the alphabet than 'P'.

    LTP schedule UIDs should contain one or more records with indicator 'P', and zero or more with
    indicators 'O' or 'C'. STP schedule UIDs should contain one or more records with indicator 'N'.
    There are some circumstances where a UID may contain both LTP and STP records - this can be down to an
    STP schedule being converted in to an LTP schedule in the next timetable period.

    This process allows a different schedule to be valid on particular days, or the service to not be valid
    on that day. For example, a schedule may be valid Monday - Friday each day of the year, but have a
    Cancellation Variation on Christmas Day and Boxing Day only.

    Scenario: LTP Single WTT schedule exists
        Given the following schedules exist
            | uid    | indicator | start_date | end_date   | days_runs |
            | A12345 | P         | 2013-01-07 | 2013-01-11 | 1111100   |
        When Uid "A12345" is selected on "2013-01-07"
        Then Schedule "A12345" indicator "P" starting on "2013-01-07" should be applied

    Scenario: LTP A variant VAR schedule overlays WTT
        Given the following schedules exist
            | uid    | indicator | start_date | end_date   | days_runs |
            | A12345 | P         | 2013-01-07 | 2013-01-11 | 1111100   |
            | A12345 | O         | 2013-01-09 | 2013-01-09 | 0011000   |
        When Uid "A12345" is selected on "2013-01-09"
        Then Schedule "A12345" indicator "O" starting on "2013-01-09" should be applied

    Scenario: LTP A cancelled CAN schedule overlays WTT
        Given the following schedules exist
            | uid    | indicator | start_date | end_date   | days_runs |
            | A12345 | P         | 2013-01-07 | 2013-01-11 | 1111100   |
            | A12345 | C         | 2013-01-09 | 2013-01-10 | 0011000   |
        When Uid "A12345" is selected on "2013-01-09"
        Then Schedule "A12345" indicator "C" starting on "2013-01-09" should be applied

    Scenario: Cancelled CAN schedule can overlay VAR and WTT
        Given the following schedules exist
            | uid    | indicator | start_date | end_date   | days_runs |
            | A12345 | P         | 2013-01-07 | 2013-01-11 | 1111100   |
            | A12345 | C         | 2013-01-09 | 2013-01-09 | 0010000   |
            | A12345 | O         | 2013-01-10 | 2013-01-10 | 0001000   |
        When Uid "A12345" is selected on "2013-01-09"
        Then Schedule "A12345" indicator "C" starting on "2013-01-09" should be applied

    Scenario: STP overlays CAN, VAR and WTT
        Given the following schedules exist
            | uid    | indicator | start_date | end_date   | days_runs |
            | A12345 | P         | 2013-01-07 | 2013-01-11 | 1111100   |
            | A12345 | C         | 2013-01-09 | 2013-01-09 | 0010000   |
            | A12345 | O         | 2013-01-10 | 2013-01-10 | 0001000   |
            | A12345 | N         | 2013-01-09 | 2013-01-10 | 0011000   |
        When Uid "A12345" is selected on "2013-01-09"
        Then Schedule "A12345" indicator "N" starting on "2013-01-09" should be applied

#    Scenario: STP overlays CAN, VAR
#        Given the following schedules exist
#            | uid    | indicator | start_date | end_date   | days_runs |
#            | K33330 | C         | 2022-11-07 | 2022-11-07 | 1000000   |
#            | K33330 | N         | 2022-11-07 | 2022-11-07 | 1000000   |
#        When Uid "K33330" is selected on "2022-11-07"
#        Then Schedule "K33330" indicator "C" starting on "2022-11-07" should be applied
