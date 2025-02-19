# How to run the test suite

To execute the tests, run:

```console
$ make test
```

If you want to filter the tests to run, you can use the `FILE` and/or the `FILTER` environment variables:

```console
$ make test FILE=tests/HomeTest.php
$ make test FILE=tests/HomeTest.php FILTER=testIndexRendersCorrectly
```

A code coverage analysis is generated under the `coverage/` folder and can be opened with your browser:

```console
$ xdg-open coverage/index.html
```

If you want to change the coverage format, you can set the `COVERAGE` environment variable.

```console
$ make test COVERAGE=--coverage-text
```

You can also execute the linters with:

```console
$ make lint
$ # OR to fix issues
$ make lint-fix
```

The test suite is automatically executed on pull requests with [GitHub Actions](https://github.com/flusio/flus.fr/actions).
You can learn more by having a look at the [workflow file](/.github/workflows/ci.yml).
