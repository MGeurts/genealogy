# Security Policy

Thank you for taking the time to responsibly disclose security issues. This document explains how to report a vulnerability to us and what to expect after you do.

---

## Reporting a Security Vulnerability

**Preferred contact:** `bin@kraweb.be`.

**Do not** open a public GitHub issue for security reports.

### What to include in your report

Please include clear, actionable information so we can quickly reproduce and triage the issue. Minimal useful reports usually contain:

-   A short summary of the issue (1–2 lines)
-   Affected component(s) and versions (e.g. `project-name v1.2.3`)
-   Exact steps to reproduce the issue (curl commands, example requests, SQL queries, or screenshots). Prefer plain text / scripts over binaries.
-   Expected vs actual behaviour
-   Any PoC (proof-of-concept) code **as text** (do not send compiled binaries). If you attach files, clearly explain them.
-   Your suggested severity (optional)
-   Your contact details and whether you consent to being credited (and how — GitHub name, real name, or anonymously)

## How we handle reports

We will treat all reports with confidentiality and aim to follow these timelines when possible:

-   **Acknowledgement:** We will acknowledge receipt within **15 business days**.
-   **Initial triage:** We will perform initial triage and classification within **30 business days**.
-   **Coordination & patching:** We will work with you to fix the issue. We will propose a disclosure timeline; commonly used windows are **30–90 days** depending on severity and complexity.

If you prefer a specific disclosure timeline (for example, you intend to request a CVE or follow a 90-day disclosure), please state that in your initial message.

## CVE requests and credit

If you want a CVE assigned to your report, we can provide you with the information needed to request a CVE from MITRE directly.

## Safe handling and boundaries

-   Do **not** send passwords, private keys, or credentials of any kind to us. If you need to demonstrate something that requires access, provide reproducible steps that use test accounts or local setups.
-   Do **not** attach compiled binaries or executables. Provide PoC as plaintext when possible.
-   If you need us to run code or reproduce an exploit on our systems, we will only do so in a controlled environment. Do not attempt any destructive actions against production systems.

## Legal safe harbour

We do not consider well-intentioned security research to be abusive if it follows these rules (no data exfiltration, no denial-of-service attacks, no further exploitation). We will not pursue legal action against a reporter acting in good faith and following this disclosure policy.

## If we don’t respond / non-cooperation

If you do not receive a reply and still believe the issue is serious, you may escalate by:

1. Re-sending the report (in case the first email was missed)
2. Contacting us on the maintainers’ public contact channels (for non-sensitive details only)
3. Requesting a CVE through MITRE yourself — we will cooperate after being contacted by MITRE.

We understand 90-day disclosure deadlines are commonly used by researchers; we will consider such timelines when you state them. We also appreciate coordinated disclosure where possible.

---

### Example (report template)

```
Subject: [SECURITY] SQL injection in `api/v1/users` - project-name v1.2.3

Summary:
A SQL injection exists in the `GET /api/v1/users` endpoint when the `q` parameter is provided.

Affected versions:
project-name v1.2.0 - v1.2.3

Reproduction steps:
1. curl -v "https://example.com/api/v1/users?q=' OR '1'='1"
2. Observe that the response contains all users

PoC (plaintext):
[plain-text payload or minimal script here]

Preferred contact: @github-username (or name)
CVE request: Yes, please assign to my name (Full Name / GitHub: github-username)

```

---
