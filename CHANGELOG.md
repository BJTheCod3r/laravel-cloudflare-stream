# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.6] - 2025-08-23
### Added
- CloudflareStream::findExactDuplicates(string $id, ?int $limit = null) to locate duplicate videos using the original video's meta.name, size, and duration. Supports optional limiting of results.
- README: Added project logo and Packagist badges (downloads, latest version, license).
- Tests: Added a feature test covering exact duplicate detection.

### Changed
- listVideos now accepts VideoQueryParams (in addition to array). VideoQueryParams::status now uses the VideoStatus backed enum and is properly serialized via an overridden toArray().