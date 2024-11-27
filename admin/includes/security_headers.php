<?php
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://mcc-lrc.com; style-src 'self'");
header_remove("X-Powered-By");
header_remove("panel");
header("Server: SecureServer");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>