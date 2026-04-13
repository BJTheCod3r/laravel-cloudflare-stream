# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.1.0](https://github.com/BJTheCod3r/laravel-cloudflare-stream/compare/v1.0.7...v1.1.0) (2026-04-13)


### Features

* **upload:** add direct file upload to Cloudflare Stream ([5f67fb7](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/5f67fb7c3b87e49fa5645571283449eb9e055a03))
* **upload:** add direct file upload to Cloudflare Stream ([1b4b0ae](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/1b4b0ae9de12f0cdf734026e9bef2145d52fee0b))

## [1.0.7](https://github.com/BJTheCod3r/laravel-cloudflare-stream/compare/v1.0.6...v1.0.7) (2026-04-12)


### Bug Fixes

* **ci:** match release-please tags to existing releases ([a1391b0](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/a1391b081c154be28501f0fb4d8b71d72defe505))
* **ci:** match release-please tags to existing releases ([e3b8ded](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/e3b8dedb249000fcabf6cc5a4ff99f189754682b))


### Miscellaneous Chores

* **laravel:** add support for versions 12 and 13 ([35d13ae](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/35d13aef1965e0c8d5a78a43927873d6e06421e8))
* **laravel:** add support for versions 12 and 13 ([9db3dd6](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/9db3dd60fac04eb259430ea899c347b82a77a3ed))
* **main:** release bjthecod3r/laravel-cloudflare-stream 1.1.0 ([01d0b13](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/01d0b13555868836cb1a2f86a19a4557be7fb89a))

## [1.0.6] - 2025-08-23
### Added
- CloudflareStream::findExactDuplicates(string $id, ?int $limit = null) to locate duplicate videos using the original video's meta.name, size, and duration. Supports optional limiting of results.
- README: Added project logo and Packagist badges (downloads, latest version, license).
- Tests: Added a feature test covering exact duplicate detection.

### Changed
- listVideos now accepts VideoQueryParams (in addition to array). VideoQueryParams::status now uses the VideoStatus backed enum and is properly serialized via an overridden toArray().
