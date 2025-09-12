# Repository Guidelines

## Project Structure & Module Organization
- Plugin type: Moodle local plugin (`local_allaccess`).
- Key paths:
  - `classes/` — PHP namespaced classes (e.g., `local_allaccess\\service\\...`).
  - `db/` — install/upgrade definitions (e.g., `install.xml`, `access.php`).
  - `lang/` — language strings (e.g., `lang/en/local_allaccess.php`).
  - Root PHP entrypoints: `buy.php`, `success.php`, config: `settings.php`, `version.php`.
- Suggested tests location: `tests/` (PHPUnit) and `tests/behat/` (Behat).

## Build, Test, and Development Commands
- Purge caches (from Moodle root): `php admin/cli/purge_caches.php`
- Run upgrade (after DB/versions): `php admin/cli/upgrade.php --non-interactive`
- Init PHPUnit (once, Moodle root): `php admin/tool/phpunit/cli/init.php`
- PHPUnit for this plugin: `vendor/bin/phpunit --testsuite local_allaccess`
- Behat (if used): `vendor/bin/behat --tags @local_allaccess`
- Code style check (from Moodle root if standards installed): `vendor/bin/phpcs --standard=moodle local/allaccess`

## Coding Style & Naming Conventions
- PHP: Moodle Coding Style (PSR-12 aligned with Moodle specifics).
- Indentation: 4 spaces; no tabs.
- Namespacing: `namespace local_allaccess\\...` for classes in `classes/`.
- File/class mapping: `classes/service/manager.php` → `local_allaccess\\service\\manager`.
- Strings via `get_string('key', 'local_allaccess')`; define keys in `lang/en/local_allaccess.php`.
- Capability constants in `db/access.php` as `local/allaccess:*`.

## Testing Guidelines
- PHPUnit: place tests in `tests/` and name `*_test.php` (e.g., `service_manager_test.php`).
- Use `advanced_testcase` for Moodle integration tests.
- Behat: feature files in `tests/behat/*.feature`; tag scenarios with `@local_allaccess`.
- Aim for meaningful coverage on services, forms, and access checks.

## Commit & Pull Request Guidelines
- Commits: imperative mood, concise scope (e.g., `fix: validate sesskey in buy.php`).
- Reference issues when applicable (e.g., `Fixes #123`).
- PRs: include summary, rationale, testing steps, screenshots (UI), and DB changes notes.
- Keep changes minimal; avoid touching Moodle core.

## Security & Configuration Tips
- Always `require_login()` and check capabilities before actions.
- Validate input with `PARAM_*`, enforce `require_sesskey()` for state-changing actions.
- Use `moodle_url`, renderers, and `get_string()` for safety and i18n.
