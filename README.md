# Laravel CA Example Application

> A complete Laravel 12 demo application showcasing the CA PKI system -- Root CA, Intermediate CAs, certificate issuance, revocation, CRL generation, and audit logging.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/groupesti/laravel-ca-example.svg)](https://packagist.org/packages/groupesti/laravel-ca-example)
[![PHP Version](https://img.shields.io/badge/php-8.4%2B-blue)](https://www.php.net/releases/8.4/en.php)
[![Laravel](https://img.shields.io/badge/laravel-12.x-red)](https://laravel.com)
[![Tests](https://github.com/groupesti/laravel-ca-example/actions/workflows/tests.yml/badge.svg)](https://github.com/groupesti/laravel-ca-example/actions/workflows/tests.yml)
[![License](https://img.shields.io/github/license/groupesti/laravel-ca-example)](LICENSE.md)

## Overview

This application demonstrates real-world usage of the following CA packages:

- **laravel-ca** -- Core CA management (CaManager, models, DTOs)
- **laravel-ca-key** -- Key generation and management
- **laravel-ca-csr** -- Certificate Signing Request creation
- **laravel-ca-crt** -- Certificate issuance, revocation, chain building
- **laravel-ca-crl** -- Certificate Revocation List generation
- **laravel-ca-audit** -- Audit trail and compliance reporting
- **laravel-ca-log** -- Structured logging
- **laravel-ca-policy** -- Certificate policy engine
- **laravel-ca-acme** -- ACME protocol support
- **laravel-ca-ocsp** -- OCSP responder
- **laravel-ca-pkcs12** -- PKCS#12 export
- **laravel-ca-oid** -- OID management
- **laravel-ca-scep** -- SCEP protocol
- **laravel-ca-est** -- EST protocol
- **laravel-ca-tsa** -- Timestamping authority
- **laravel-ca-cms** -- CMS/PKCS#7 operations
- **laravel-ca-ui** -- UI components

## What It Demonstrates

- **CA Hierarchy**: Root CA with two Intermediate CAs (TLS and Code Signing)
- **Certificate Issuance**: Server TLS, Client mTLS, and Code Signing certificates
- **Certificate Revocation**: Revoke certificates with standard RFC reasons
- **CRL Generation**: Generate CRLs for any CA
- **Audit Logging**: Full audit trail of all PKI operations
- **Key Management**: RSA-4096 for CAs, RSA-2048 for end-entity certificates

## Requirements

- PHP 8.4+
- Composer 2
- ext-openssl
- SQLite (included by default)

## Setup

```bash
# Clone the repository
cd packages/laravel-ca-example

# Install dependencies
composer install

# Copy environment file
cp .env.example .env
php artisan key:generate

# Run the automated setup (creates DB, runs migrations, seeds demo data)
php artisan ca-example:setup
```

## Running the Application

```bash
php artisan serve
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

## Available Routes

| Route | Description |
|---|---|
| `GET /` | Dashboard with stats and recent activity |
| `GET /cas` | List all Certificate Authorities |
| `GET /cas/{uuid}` | CA detail with issued certificates |
| `GET /certificates` | List all certificates |
| `GET /certificates/{uuid}` | Certificate detail with PEM display |
| `GET /audit` | Audit log with filtering |
| `POST /demo/issue-cert` | Issue a new certificate |
| `POST /demo/revoke-cert` | Revoke a certificate |
| `POST /demo/generate-crl` | Generate a CRL for a CA |

## Artisan Commands

| Command | Description |
|---|---|
| `php artisan ca-example:setup` | Initial setup: migrations + seed data |
| `php artisan ca-example:setup --fresh` | Fresh setup: drop all tables first |
| `php artisan ca-example:reset` | Reset: drop everything and re-seed |
| `php artisan ca-example:status` | Show current CA hierarchy and statistics |

## Demo PKI Hierarchy

After setup, the following hierarchy is created:

```
Demo Root CA (RSA-4096, 10-year validity)
+-- Demo TLS Intermediate CA (RSA-4096, 5-year validity)
|   +-- demo.example.com (Server TLS, 1-year validity)
|   +-- Demo User (Client mTLS, 1-year validity)
+-- Demo Code Signing CA (RSA-4096, 5-year validity)
    +-- Demo Developer (Code Signing, 1-year validity)
```

## Configuration

The CA system configuration is in `config/ca.php`:

| Key | Default | Description |
|---|---|---|
| `serial_number.bytes` | `20` | Byte length for serial number generation |
| `defaults.key_algorithm` | `rsa-4096` | Default key algorithm for new CAs |
| `defaults.hash_algorithm` | `sha256` | Default hash algorithm for signing |
| `defaults.validity.root_ca` | `3650` | Root CA validity in days |
| `defaults.validity.intermediate_ca` | `1825` | Intermediate CA validity in days |
| `defaults.validity.end_entity` | `365` | End-entity cert validity in days |
| `storage.driver` | `database` | Key/cert storage driver |
| `encryption.strategy` | `app_key` | Private key encryption strategy |
| `multi_tenant.enabled` | `false` | Multi-tenancy support |

## Testing

```bash
./vendor/bin/pest
```

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email security@groupesti.com instead of using the issue tracker.

## Credits

- [Groupe STI](https://github.com/groupesti)

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.
