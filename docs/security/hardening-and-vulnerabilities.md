# WordPress security and known vulnerability patterns

Security is a process: reduce attack surface, keep supported software current,
grant minimal access, monitor changes, and maintain a tested recovery path. A
security plugin can add useful controls but cannot compensate for vulnerable
custom code or an unmaintained server.

## Common vulnerability classes

| Risk | Typical cause | Primary control |
| --- | --- | --- |
| Stored/reflected XSS | Printing untrusted data as HTML | Validate/sanitize input and escape for the output context |
| SQL injection | Concatenating request data into SQL | WordPress APIs or `$wpdb->prepare()` with strict validation |
| CSRF | State changes without intent verification | Nonce **and** capability check |
| Broken access control / IDOR | Trusting an object ID or hidden field | Authorize the current user for the specific object/action |
| Arbitrary file upload | Weak extension/MIME/path rules | Use upload APIs, allowlists, capabilities, and non-executable storage |
| Path traversal / inclusion | User-controlled file paths | Map identifiers to fixed paths; never include arbitrary input |
| SSRF | Fetching user-provided URLs | Allowlist destinations and use safe HTTP APIs |
| Unsafe deserialization | `unserialize()` on untrusted bytes | Use JSON/scalars and reject unexpected types |
| Privilege escalation | Misused roles/capabilities | Check capabilities at each privileged operation |
| Secret leakage | Credentials in Git, logs, or public backups | Secret management, log minimization, rotation, access control |

Nonces mitigate cross-site request forgery; they do not prove authorization and
are not single-use secrets. Sanitization changes data into an acceptable form,
validation accepts or rejects it, and escaping belongs at final output.

## Baseline hardening

- Apply supported WordPress core, plugin, theme, PHP, database, and OS updates
  after backup and compatibility testing.
- Remove unused themes/plugins and reject abandoned components.
- Require unique accounts, least privilege, strong authentication, and MFA for
  privileged users. Review accounts and application passwords periodically.
- Enforce HTTPS, secure cookies, conservative file permissions, and deny script
  execution in upload/cache directories at the server layer.
- Protect backups outside the public web root and test clean restoration.
- Add rate limiting and monitoring at the edge/server without relying on hidden
  login URLs as a security boundary.
- Disable dashboard file editing in managed production environments and manage
  code through reviewed deployments.

## Vulnerability response

Do not maintain a static list of “known vulnerable plugins” here; it becomes
dangerously stale. Consult current vendor advisories, the WordPress.org plugin
page/changelog, the Wordfence Intelligence or Patchstack databases, and the WPScan
database. Confirm the affected version range and whether exploitation requires
authentication.

If compromise is suspected: preserve evidence, restrict access, rotate secrets
from a clean device, identify persistence and scope, rebuild from trusted sources,
restore only verified data, patch the entry point, and monitor. Simply deleting
the visible payload or installing a security plugin is not eradication.
