# Open Badges Backend

Backend code and schema for Open Badges.

Note this only includes the bare minimum to set up a database to hold issuer,
earner and badge data. There is no admin or user frontend.

In the database schema, we have been stricter than the standard requires in some
areas, making optional fields NOT NULL and therefore required. In addition,
some optional fields have been omitted.

## Unit Tests

Unit tests can be found in the `tests` directory and require PHPUnit. The full
test suite can be run by executing `phpunit` in the root directory. All
test configuration is done via the `phpunit.xml` file.

Automatically running a development web server is based on the following blog
post:

http://tech.vg.no/2013/07/19/using-phps-built-in-web-server-in-your-test-suites/
