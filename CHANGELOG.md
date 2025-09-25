# Changelog

All notable changes to the Apipedia PHP SDK will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release of Apipedia PHP SDK
- WhatsApp messaging support with text and media
- Bulk messaging capabilities (V1 and V2)
- Telegram bot integration with full feature support
- SMS services (Regular, VIP, OTP, VVIP)
- AI chat integration with chainable responses
- Profile management and presence updates
- Message status tracking and monitoring
- Fluent API with method chaining
- Comprehensive test suite with PHPUnit
- Full documentation and examples
- Helper function for easy instantiation
- Error handling with custom exceptions
- Support for file uploads via URL, path, or stream

### Features
- **WhatsApp API**: Send text messages and media attachments
- **Bulk Messaging**: Send same or different messages to multiple recipients
- **Telegram Integration**: Complete bot functionality including buttons, locations, documents
- **SMS Services**: Multiple priority levels and OTP support
- **AI Chat**: Interact with AI agents and chain responses
- **Cross-Platform**: Send AI responses across WhatsApp, Telegram, and SMS
- **Status Tracking**: Monitor message delivery and read receipts
- **Presence Management**: Update typing and online status
- **Method Chaining**: Fluent interface for complex workflows

### Technical
- PHP 7.4+ compatibility
- PSR-4 autoloading
- Guzzle HTTP client for reliable API communication
- Comprehensive error handling
- Full test coverage with mocked HTTP responses
- PHPStan level 8 static analysis ready
- PSR-12 code style compliance

## [1.0.0] - 2024-01-01

### Added
- Initial public release
- Core functionality for all supported APIs
- Documentation and examples
- Test suite
- MIT License