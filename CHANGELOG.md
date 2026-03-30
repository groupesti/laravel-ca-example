# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2026-03-29

### Added

- Initial release of the Laravel CA Example package.
- `ExampleServiceProvider` with config publishing and Artisan command registration.
- `ca-example:setup` command to bootstrap a complete demo CA environment.
- `ca-example:reset` command to clear all demo data.
- `ca-example:demo` command with interactive scenarios (pki, tls, mtls, code-signing).
- `DemoRootCaSeeder` for creating a demo Root CA.
- `DemoIntermediateCaSeeder` for creating demo Intermediate CAs (TLS, Client Auth, Code Signing, Email).
- `DemoCertificateSeeder` for creating demo end-entity certificates.
- `DemoFullPkiSeeder` that orchestrates the full PKI hierarchy creation.
- `CertificateAuthorityFactory` immutable factory for building test CA configurations.
- `CertificateFactory` with static constructors for TLS server, mTLS client, code signing, and S/MIME.
- `KeyPairFactory` with static constructors for RSA and EC key algorithms.
- `CsrFactory` for building test CSR configurations.
- `InternalPkiConfig` sample configuration for internal PKI deployments.
- `WebTlsConfig` sample configuration for web TLS certificates.
- `MtlsConfig` sample configuration for mutual TLS client certificates.
- `CodeSigningConfig` sample configuration for code signing certificates.
- `LogCertificateIssued` example event listener for certificate issuance logging.
- `NotifyCaCreated` example event listener for CA creation notifications.
- `DemoConfiguration` readonly DTO for demo settings.
- `ExampleException` with static factory methods for common error scenarios.
- Configuration file `config/ca-example.php` with all demo settings.
- Unit tests for `CertificateAuthorityFactory`.
