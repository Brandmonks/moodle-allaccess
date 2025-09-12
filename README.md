# All-Access (Moodle local plugin)

A simple purchase page that grants “All-Access” via a site cohort. It can use Moodle’s core Payment modal or link to an external checkout page, and supports on-page editing (title, HTML, and a {{buybutton}} placeholder).

## Features
- Buy page at `/local/allaccess/buy.php` with editable title and content.
- {{buybutton}} placeholder renders the live Buy button; if omitted, it is appended.
- Choose core Payment modal or an external URL for checkout.
- Hides the Buy button when the user already belongs to the configured cohort.

## Requirements
- Moodle 4.0+.
- A configured Payment account (if using the core Payment modal).

## Installation
1. Place this directory at `local/allaccess`.
2. From your Moodle root:
   - `php admin/cli/upgrade.php --non-interactive`
   - `php admin/cli/purge_caches.php`

## Configuration
Site administration → Plugins → Local plugins → All-Access
- Price and Currency: used by the core Payment modal.
- Payment account: select a site payment account.
- All-Access cohort: cohort that represents “purchased” users.
- Use external buy website: toggle to bypass the modal.
- External buy URL: absolute URL (e.g., https://example.com/checkout).

## Editing the Buy Page
- Turn Edit mode on (top right) and open `/local/allaccess/buy.php`.
- Click “Edit page” to set:
  - Page title.
  - Page content (HTML). Use `{{buybutton}}` where the button should appear.
- If no content exists, a default template is provided.

## Creating a Cohort
1. Site administration → Users → Accounts → Cohorts → Add new cohort.
2. Context: System.
3. Name it (e.g., “All-Access”) and Save.
4. In All-Access plugin settings, select this cohort under “All-Access cohort”.

Tip: Membership in this cohort unlocks access in your courses; use cohort-based enrolment or groupings as needed.

## Usage
- Link to `/local/allaccess/buy.php` from menus, blocks, or course pages.
- After purchase, add users to the selected cohort (manually or via your payment flow). The page will then show “You already have All-Access”.

## Capabilities
- `local/allaccess:buy` (allow for users/guests to see the page).
- `local/allaccess:configure` (managers edit page and settings).

## Troubleshooting
- “No payment gateways available”: set a Payment account and supported currency; check gateway config (Site administration → Plugins → Payment gateways).
- Button not visible but should be: ensure the user is not already in the cohort; clear caches.
