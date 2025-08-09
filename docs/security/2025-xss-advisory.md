# Security Advisory – Reflected and Stored XSS Vulnerabilities

## Summary

Two authenticated Cross-Site Scripting (XSS) vulnerabilities were identified and reported in the Genealogy application:

-   **Reflected XSS:** Malicious input could be injected into query parameters and reflected back to the browser without proper sanitization.
-   **Stored XSS:** User-supplied content could be stored and later rendered without escaping, enabling persistent attacks.

A potential **unauthenticated file disclosure** was also noted but is still under investigation and not included in this advisory.

---

## Impact

An attacker with authenticated access could execute arbitrary JavaScript in another user’s browser session, potentially leading to:

-   Session hijacking
-   Data exfiltration
-   UI redressing

---

## Affected Versions

All versions prior to `v4.4.0.

---

## Patched Versions

Fixed in `v4.4.0.

---

## Mitigation

Upgrade to the patched version.  
For those unable to upgrade immediately:

-   Escape or sanitize all user-supplied content before rendering.
-   Review and secure file storage configurations to prevent direct access to sensitive files.

---

## Credit

These vulnerabilities were discovered and responsibly disclosed by **Adrian** (eternalvalhalla), whose contribution significantly improved the application’s security.

---

## References

-   [Commit(s) fixing the issue](https://github.com/MGeurts/genealogy/commit/1683b3cbea5e52c99291fa231b7bc8c33f33c33f)
-   [OWASP XSS Prevention Cheat Sheet](https://owasp.org/www-community/xss-prevention)

---

# CVE Request Draft

**Title:** Reflected and Stored XSS Vulnerabilities in Genealogy Application

**Description:**  
Two authenticated Cross-Site Scripting (XSS) vulnerabilities were identified in the Genealogy application.

1. **Reflected XSS:** Malicious input could be injected into query parameters and reflected back to the browser without proper sanitization.
2. **Stored XSS:** User-supplied content could be stored and later rendered without escaping, enabling persistent attacks.

The unauthenticated file disclosure issue is not included in this CVE request as it is still under investigation.

**Impact:**  
Authenticated attackers could run arbitrary JavaScript in another user’s session, leading to session hijacking, data theft, and UI manipulation.

**Affected Versions:**  
All versions prior to `vX.Y.Z` (replace with patched version).

**Patched Versions:**  
Fixed in `vX.Y.Z`.

**Mitigation:**  
Upgrade to the patched version.  
If upgrading is not possible:

-   Escape/sanitize all user-provided data before rendering.
-   Secure file storage to prevent unauthenticated access.

**Credit:**  
Reported and responsibly disclosed by **Adrian** (eternalvalhalla).

**References:**

-   [Commit(s) fixing the issue](https://github.com/MGeurts/genealogy/commit/1683b3cbea5e52c99291fa231b7bc8c33f33c33f)
-   [OWASP XSS Prevention Cheat Sheet](https://owasp.org/www-community/xss-prevention)
