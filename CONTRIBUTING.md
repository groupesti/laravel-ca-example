# Contributing

Thank you for considering contributing to Laravel CA Example! This document provides guidelines and instructions for contributing.

## Prerequisites

- PHP 8.4+
- Composer 2
- Git

## Setup

```bash
git clone https://github.com/groupesti/laravel-ca-example.git
cd laravel-ca-example
composer install
```

## Branching Strategy

- `main` — stable, released code
- `develop` — work in progress
- `feat/` — new features (branch from `develop`)
- `fix/` — bug fixes (branch from `develop`)
- `docs/` — documentation updates

## Coding Standards

This project uses **Laravel Pint** with the `@laravel` ruleset:

```bash
./vendor/bin/pint
./vendor/bin/pint --test  # Check without fixing
```

**PHPStan** level 9 for static analysis:

```bash
./vendor/bin/phpstan analyse
```

## Tests

Tests are written with **Pest 3**:

```bash
./vendor/bin/pest
./vendor/bin/pest --coverage  # With coverage report
```

Minimum coverage threshold: **80%**.

## Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` — new feature
- `fix:` — bug fix
- `docs:` — documentation changes
- `chore:` — maintenance tasks
- `refactor:` — code refactoring without behavior change
- `test:` — adding or updating tests

Examples:

```
feat: add S/MIME sample configuration
fix: correct key algorithm validation in KeyPairFactory
docs: update factory usage examples in README
test: add unit tests for CsrFactory
```

## Pull Request Process

1. Fork the repository.
2. Create a feature branch from `develop`.
3. Make your changes.
4. Ensure all checks pass:
   - `./vendor/bin/pest`
   - `./vendor/bin/pint --test`
   - `./vendor/bin/phpstan analyse`
5. Update `CHANGELOG.md` under `[Unreleased]`.
6. Submit a PR to `develop` using the PR template.

## PHP 8.4 Specifics

When contributing, leverage PHP 8.4 features where appropriate:

- **Readonly classes and properties** for DTOs and value objects
- **Constructor property promotion** for concise constructors
- **Named arguments** for improved readability
- **`match` expressions** instead of switch statements
- **Union types and intersection types** for precise type declarations
- **Enums** instead of class constants for fixed sets of values
- **`#[\Override]` attribute** on overridden methods

## Code of Conduct

Please review our [Code of Conduct](CODE_OF_CONDUCT.md) before contributing.
