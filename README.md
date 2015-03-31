# Open Badges Backend

Backend code and schema for Open Badges.

Note this only includes the bare minimum to set up a database to hold issuer,
earner and badge data. There is no admin or user frontend.

In the database schema, we have been stricter than the standard requires in some
areas, making optional fields NOT NULL and therefore required. In addition,
some optional fields have been omitted.

This is intended to be a backend which is simple to deploy on existing
infrastructure, e.g. as an Apache VirtualHost.

The backend assumes that you will be using hosted assertions, as these are
simpler than signed assertions.

## Unit Tests

Unit tests can be found in the `tests` directory and require PHPUnit. The full
test suite can be run by executing `phpunit` in the root directory. All
test configuration is done via the `phpunit.xml` file.

Automatically running a development web server is based on the following blog
post:

http://tech.vg.no/2013/07/19/using-phps-built-in-web-server-in-your-test-suites/

## Authentication

There is no authentication built into Open Badges - as a RESTful API it assumes
that you will add an authentication layer on top, for example using Basic
or Digest Authentication. Both can be enabled without any changes to the code
as the authentication process is handled by the web server before the request
is passed on to Open Badges.
