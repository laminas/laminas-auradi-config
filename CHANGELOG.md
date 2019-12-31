# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

Versions prior to 0.2.0 were released as the package "webimpress/laminas-auradi-config".

## 1.0.0 - 2018-03-15

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [zendframework/zend-auradi-config#3](https://github.com/zendframework/zend-auradi-config/pull/3)
  removes support for PHP versions prior to PHP 7.1.

### Fixed

- [zendframework/zend-auradi-config#6](https://github.com/zendframework/zend-auradi-config/pull/6) fixes an
  issue with invokables that are defined such that the key and the value differ.
  In such cases, the key should be an alias to the invokable class.

## 0.2.2 - 2018-02-26

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-auradi-config#6](https://github.com/zendframework/zend-auradi-config/pull/6) fixes an
  issue with invokables that are defined such that the key and the value differ.
  In such cases, the key should be an alias to the invokable class.

## 0.2.1 - 2018-01-23

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-auradi-config#5](https://github.com/zendframework/zend-auradi-config/pull/5) fixes an
  issue whereby factories would not receive the service name as the second
  argument, preventing their re-use for additional services.

## 0.2.0 - 2017-11-21

### Added

- Nothing.

### Changed

- Renames the package to zendframework/zend-auradi-config.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 0.1.1 - 2017-09-27

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-auradi-config#1](https://github.com/webimpress/laminas-auradi-config/pull/1) fixes an issue with lowest
  dependencies, when deprecated interop-container was used, instead of PSR-11 container.

## 0.1.0 - 2017-09-27

Initial Release.

### Added

- Everything.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
