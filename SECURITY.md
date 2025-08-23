# Security Policy

## Supported Versions

We release security patches for the latest stable version of the application.  
Please update to the most recent release to ensure you receive the latest security fixes.

| Version | Supported |
| ------- | --------- |
| Latest  | ✅        |
| Older   | ❌        |

---

## Reporting a Vulnerability

If you discover a security vulnerability, please **do not** create a public GitHub issue.  
Instead, report it privately via one of the following methods:

-   **GitHub Security Advisories** (preferred): [Open a new draft advisory](../../security/advisories/new)
-   **Email**: bin@kreaweb.be

When reporting, please include:

1. A detailed description of the vulnerability.
2. Steps to reproduce the issue.
3. The potential impact (e.g., data loss, remote code execution).
4. Suggested fixes (if available).
5. Any relevant screenshots or proof-of-concept code.

---

## Security Advisory Process

1. We will acknowledge receipt of your report within **3 working days**.
2. We will work on verifying and reproducing the vulnerability.
3. We will aim to release a patch within **14 working days** of verification (complex cases may take longer).
4. Once fixed, we will publish a GitHub Security Advisory and, if applicable, request a CVE ID.
5. We will credit the reporter publicly (unless you request otherwise).

---

## Credit and Recognition

We value responsible disclosure and will credit all verified security researchers in:

-   The GitHub Security Advisory.
-   This repository’s `SECURITY.md` acknowledgements section.
-   CVE records (if a CVE is issued).

---

## Acknowledgements

We thank the following security researchers for their responsible disclosure and contributions:

-   _Adrian (@eternalvalhalla)_

---

## Past Security Advisories

This section lists previously disclosed vulnerabilities, their impact, and who reported them.

| Advisory ID / CVE     | Description                                       | Affected Versions | Patched Version | Reported By               | Advisory Link                                         |
| --------------------- | ------------------------------------------------- | ----------------- | --------------- | ------------------------- | ----------------------------------------------------- |
| `GHSA-3h8x-g9xj-rhwg` | Reflected XSS Vulnerability                       | < `v4.4.0`        | `v4.4.0`        | Adrian (@eternalvalhalla) | [View](../../security/advisories/GHSA-3h8x-g9xj-rhwg) |
| `GHSA-j457-9m86-6q5r` | Stored XSS vulnerability in Genealogy application | < `v4.4.0`        | `v4.4.0`        | Adrian (@eternalvalhalla) | [View](../../security/advisories/GHSA-j457-9m86-6q5r) |
