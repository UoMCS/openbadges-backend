# Installation

Basic installation can be achieved by simply cloning the Git repository.

## Dependencies

### PHP version

PHP 5.4 or later is required, as the test scripts rely on the built-in web
server functionality.

### Silex

The Silex framework is used for routing in `src/htdocs/index.php`. It can be
downloaded from:

http://silex.sensiolabs.org/

The full framework should be extracted to `src/vendor`.

## Configuration

Open `src/config-sample.php` and edit the options to match your setup, then
save it as `src/config.php`.
