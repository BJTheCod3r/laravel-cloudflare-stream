# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.1.0](https://github.com/BJTheCod3r/laravel-cloudflare-stream/compare/bjthecod3r/laravel-cloudflare-stream-v1.0.6...bjthecod3r/laravel-cloudflare-stream-v1.1.0) (2026-04-11)


### Features

* added new method findExactDuplicates ([42e15ad](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/42e15add6c27db0ebea94b7970a663b644ecb32d))
* added new method findExactDuplicates ([3c579f1](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/3c579f1ce05c7ca1bc155cde8fa88013052386de))
* added new method findExactDuplicates ([0532c26](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/0532c26f0ce71f4573065211fea6f35643113d4a))


### Miscellaneous Chores

* added support for illuminate/support v11 ([f1310c8](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/f1310c83ba2ec11cac949d743cf6d24c562a6415))
* added support for illuminate/support v11 ([74adc4a](https://github.com/BJTheCod3r/laravel-cloudflare-stream/commit/74adc4a3b7cbf8c58cc386ab296c72d8dd09bb47))

## [1.0.6] - 2025-08-23
### Added
- CloudflareStream::findExactDuplicates(string $id, ?int $limit = null) to locate duplicate videos using the original video's meta.name, size, and duration. Supports optional limiting of results.
- README: Added project logo and Packagist badges (downloads, latest version, license).
- Tests: Added a feature test covering exact duplicate detection.

### Changed
- listVideos now accepts VideoQueryParams (in addition to array). VideoQueryParams::status now uses the VideoStatus backed enum and is properly serialized via an overridden toArray().
