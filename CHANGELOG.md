# Instagram Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.2.1 - 2020-06-10
### Changed
- Bad luck, Instagram just started requiring authentication to access the information of a media in specific regions, so the update from 1.2.0 was reverted

## 1.2.0 - 2020-06-09
### Added
- Added caption and type in `getMediaFromUrls` method

## 1.1.0 - 2020-05-22
### Added
- Improved performance by caching all media requests

### Changed
- Require all Settings fields

## 1.0.2 - 2020-05-22
### Added
- Add “type” to media information fetched with API
- Store "username" in DB record

### Changed
- Log warnings instead of info
- Updated README

### Fixed
- Fixed issue with Install migration

## 1.0.1 - 2020-05-22
### Changed
- Improved Instagram URL regex
- Removed debug code

## 1.0.0 - 2020-05-21
### Added
- Initial release
