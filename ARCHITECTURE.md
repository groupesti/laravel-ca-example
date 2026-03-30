# Architecture

## Overview

The `laravel-ca-example` package provides ready-to-use demo data, factories, sample configurations, and example event listeners for the Laravel CA system. Its purpose is to help developers quickly get started with the CA packages by providing working examples and test utilities.

This package does **not** implement any cryptographic operations itself. It orchestrates the other CA packages (`laravel-ca`, `laravel-ca-key`, `laravel-ca-crt`, `laravel-ca-csr`, `laravel-ca-crl`) to demonstrate common workflows.

## Directory Structure

```
src/
├── Console/
│   └── Commands/
│       ├── ExampleDemoCommand.php      # Interactive demo of CA operations
│       ├── ExampleResetCommand.php     # Clears all demo data
│       └── ExampleSetupCommand.php     # Bootstraps complete demo environment
├── DTOs/
│   └── DemoConfiguration.php           # Readonly DTO for demo settings
├── Exceptions/
│   └── ExampleException.php            # Package-specific exceptions
├── Factories/
│   ├── CertificateAuthorityFactory.php # Immutable builder for CA test data
│   ├── CertificateFactory.php          # Immutable builder for certificate test data
│   ├── CsrFactory.php                  # Immutable builder for CSR test data
│   └── KeyPairFactory.php              # Immutable builder for key pair test data
├── Listeners/
│   ├── LogCertificateIssued.php        # Example: log certificate issuance
│   └── NotifyCaCreated.php             # Example: notify on CA creation
├── Samples/
│   ├── CodeSigningConfig.php           # Sample config for code signing
│   ├── InternalPkiConfig.php           # Sample config for internal PKI
│   ├── MtlsConfig.php                  # Sample config for mutual TLS
│   └── WebTlsConfig.php               # Sample config for web TLS
├── Seeders/
│   ├── DemoCertificateSeeder.php       # Creates demo end-entity certificates
│   ├── DemoFullPkiSeeder.php           # Orchestrates full PKI hierarchy creation
│   ├── DemoIntermediateCaSeeder.php    # Creates demo Intermediate CAs
│   └── DemoRootCaSeeder.php            # Creates demo Root CA
└── ExampleServiceProvider.php          # Registers config and commands
```

## Service Provider

`ExampleServiceProvider` registers:

- **Config**: Merges `config/ca-example.php` with the application config.
- **Publishable config**: Allows `vendor:publish --tag=ca-example-config`.
- **Artisan commands**: `ca-example:setup`, `ca-example:reset`, `ca-example:demo` (console only).

No routes, views, migrations, or service bindings are registered.

## Key Classes

### DemoConfiguration (DTO)
A `readonly` class that encapsulates all demo settings from the config file. Created via `DemoConfiguration::fromConfig()`. Provides helper methods for generating subject arrays and common names.

### Factories (Immutable Builders)
All factories follow an immutable builder pattern — each `with*()` method returns a new clone. This prevents accidental mutation and allows fluent, reusable configuration chains.

- `CertificateAuthorityFactory` — builds CA configurations (root or intermediate)
- `CertificateFactory` — builds certificate configurations with static constructors for common types
- `KeyPairFactory` — builds key pair configurations with static constructors for common algorithms
- `CsrFactory` — builds CSR configurations

### Seeders
Seeders create demo data in the correct order:

1. `DemoRootCaSeeder` — creates the self-signed root
2. `DemoIntermediateCaSeeder` — creates purpose-specific intermediates
3. `DemoCertificateSeeder` — creates end-entity certificates
4. `DemoFullPkiSeeder` — orchestrates all three in sequence

### Sample Configs
Static classes that return configuration arrays for common PKI use cases. These serve as reference implementations that developers can adapt.

### Event Listeners
Example implementations showing how to hook into CA events. These are not registered automatically — developers must wire them in their own `EventServiceProvider`.

## Design Decisions

- **2026-03-29**: Used immutable builder pattern for factories instead of mutable setters, to prevent accidental state sharing in test suites.
- **2026-03-29**: Seeders accept a `DemoConfiguration` DTO rather than reading config directly, enabling dependency injection and testability.
- **2026-03-29**: Sample configs are `final` classes with static `toArray()` methods rather than plain arrays in config files, to allow type-safe documentation via PHPDoc.
- **2026-03-29**: Event listeners are not auto-registered — developers should opt-in explicitly to avoid unexpected side effects.
- **2026-03-29**: No routes or views needed — this is a developer tooling package, not a user-facing one.

## PHP 8.4 Features Used

- **`readonly` class** — `DemoConfiguration` DTO
- **Constructor property promotion** — all DTOs and seeders
- **Named arguments** — factory static constructors and seeder calls
- **`match` expressions** — command routing in `ExampleDemoCommand`
- **`#[\Override]` attribute** — `TestCase` overridden methods
- **`const` in `enum` expressions** — `INTERMEDIATE_PURPOSES` typed array constant

## Extension Points

- **Custom seeders**: Extend or replace any seeder by creating your own class that follows the same interface.
- **Factory customization**: Chain `with*()` methods to override any factory default.
- **Sample configs**: Copy and modify sample configuration arrays for your specific needs.
- **Event listeners**: Use the example listeners as templates for your own CA event handling.
