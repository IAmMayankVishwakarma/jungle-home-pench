<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

## Project context

Before implementing a design or feature, read [PROJECT_CONTEXT.md](./PROJECT_CONTEXT.md). Use it as the short project map: start with the listed files and inspect only the code needed for the request instead of scanning the whole repository.

For design work, follow the existing visual system in the context guide. Do not introduce a new brand, palette, typography, or UI framework unless the user asks for a rebrand or a deliberate change.

After completing a feature, update the relevant entry in `PROJECT_CONTEXT.md` with what changed, where it lives, and how it was verified. Keep it concise and accurate: distinguish working functionality from static/mockup screens and in-progress work, and treat application code as the source of truth.
