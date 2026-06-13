# CI/CD Pipeline for Laravel Application

## Overview

This CI/CD pipeline automates testing, static analysis, linting, and deployment
simulation for the Laravel application. It is triggered on every push and pull
request to the long-lived branches: `dev`, `qa`, and `master`.

## Pipeline Stages

### 1. Tests (`tests`)

Runs the Laravel test suite with code coverage reporting.

- **Framework**: Pest PHP
- **Coverage tool**: Xdebug + PHPUnit Clover report
- **Gate**: Pipeline fails if code coverage < 50% or any test fails
- **Database**: SQLite in-memory (via `.env.ci`)

### 2. Static Analysis (`static-analysis`)

Performs static code analysis using PHPStan (Larastan).

- **Tool**: `larastan/larastan` (PHPStan wrapper for Laravel)
- **Level**: 5
- **Gate**: Pipeline fails on any error-level violation (warnings are allowed)

### 3. Linting (`lint`)

Checks code style using Laravel Pint with PSR-12 / Laravel preset.

- **Tool**: `laravel/pint`
- **Long-lived branches** (dev, qa, master): Runs in `--test` mode — pipeline
  fails on any violation. No auto-fix.
- **Other branches**: Runs with auto-fix enabled. Any fixes are committed back
  to the branch.

### 4. Deploy Simulation (`deploy-simulation`)

Simulates deployment by copying the appropriate environment file.

| Branch | Environment File | Message |
|--------|----------------|---------|
| dev    | `.env.dev`     | Deploying to DEV with .env.dev |
| qa     | `.env.uat`     | Deploying to UAT with .env.uat |
| master | `.env.prod`    | Deploying to PROD with .env.prod |

- Runs only if all previous stages (`tests`, `static-analysis`, `lint`) succeed.
- **Master branch**: Requires manual approval via GitHub Environments
  (production environment with required reviewers).

### 5. Notification (`notify`)

Sends pipeline results to maintainers after deployment simulation.

- Displays branch, commit SHA, author, status, and trigger event.

## Environment Configuration Files

| File | Purpose |
|------|---------|
| `.env.ci` | CI pipeline (SQLite in-memory, no debug) |
| `.env.dev` | Development environment |
| `.env.uat` | User Acceptance Testing environment |
| `.env.prod` | Production environment |

## Required Variables

All environment files include these required variables:

- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `APP_KEY` (may be left blank — generated via `php artisan key:generate`)

## Branch Strategy

- `master` — production-ready code (requires manual approval for deploy)
- `qa` — pre-release testing
- `dev` — active development

## Setup Instructions

1. Create a GitHub repository
2. Create branches: `master`, `dev`, `qa`
3. Set up GitHub Environments:
   - `production` — with required reviewers for manual approval
   - `uat` — no approval needed
   - `development` — no approval needed
4. Push the code to trigger the pipeline
