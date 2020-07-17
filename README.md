# The Third Rail - Schedule Validity

![CI](https://github.com/third-rail-packages/schedule-validity/workflows/CI/badge.svg)

BDD Scenarios to determine which Network Rail Schedule or Association UID is applicable on a given date.

## Installation

### via Composer

Install [Composer](https://getcomposer.org/doc/00-intro.md)  and require the package with the below command.

```bash
composer.phar require third-rail-packages/schedule-validity
```

## Getting Started

The intended purpose of this packages is to document with examples how Network Rail Schedule or Associations should be selected for a given date.

The package can be consumed as part of a project by implementing the `\ThirdRailPackages\ScheduleValidity\Schedule` interface. See the `examples` directory for a very basic implementation. 

## Development

The development environment and dependencies are managed with [Docker](https://www.docker.com/get-started). In the same directory as the checked out cloned repository, run the below command to start the Docker Compose environment.

```bash
docker-compose up -d --build
```

Login to the `console` container begin development.
```
docker-compose run --rm console sh
```

Install the [Composer](https://getcomposer.org/) dependencies and execute the test suite.
```
composer.phar install
composer.phar ci
```

## Authors

- **Ben McManus** - [bennoislost](https://github.com/bennoislost)

See also the list of [contributors](https://github.com/third-rail-packages/data-feeds-queue-subscriber/contributors) who participated in this project


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

- [https://groups.google.com/forum/#!forum/openraildata-talk](https://groups.google.com/forum/#!forum/openraildata-talk)
- [https://wiki.openraildata.com/](https://wiki.openraildata.com/)
