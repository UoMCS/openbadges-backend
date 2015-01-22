# Open Badges Backend

Backend code and schema for Open Badges.

Note this only includes the bare minimum to set up a database to hold issuer,
earner and badge data. There is no admin or user frontend.

In the database schema, we have been stricter than the standard requires in some
areas, making optional fields NOT NULL and therefore required. In addition,
some optional fields have been omitted.

## Unit Tests

Unit tests can be found in the `tests` directory and require PHPUnit. The full
test suite can be run using the following command in the root directory of the
project (assuming `phpunit` is installed and in your path):

```
phpunit --bootstrap src/autoload.php tests/
```
