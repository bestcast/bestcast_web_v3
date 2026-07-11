// referral-capture.js
const REF_KEY = "bestcastReferralCode";
const REF_TIME_KEY = "bestcastReferralCapturedAt";
const EXPIRY_DAYS = 30;
const REF_CODE_PATTERN = /^[A-Za-z0-9]{6,20}$/; // alphanumeric, 6-20 chars

function isValidReferralCode(code) {
  return typeof code === "string" && REF_CODE_PATTERN.test(code.trim());
}

function captureReferralCode() {
  const params = new URLSearchParams(window.location.search);
  const refCode = params.get("ref");

  if (refCode) {
    const trimmed = refCode.trim();
    if (isValidReferralCode(trimmed)) {
      localStorage.setItem(REF_KEY, trimmed);
      localStorage.setItem(REF_TIME_KEY, Date.now().toString());
    } else {
      console.warn("Invalid referral code format, ignoring:", trimmed);
      // Do not store malformed codes
    }
  }
}

function getStoredReferralCode() {
  const code = localStorage.getItem(REF_KEY);
  const capturedAt = parseInt(localStorage.getItem(REF_TIME_KEY) || "0", 10);

  if (!code || !capturedAt) return null;
  if (!isValidReferralCode(code)) {
    // Defensive check — clear any bad data that might have slipped in earlier
    localStorage.removeItem(REF_KEY);
    localStorage.removeItem(REF_TIME_KEY);
    return null;
  }

  const ageMs = Date.now() - capturedAt;
  const expiryMs = EXPIRY_DAYS * 24 * 60 * 60 * 1000;

  if (ageMs > expiryMs) {
    localStorage.removeItem(REF_KEY);
    localStorage.removeItem(REF_TIME_KEY);
    return null;
  }
  return code;
}

document.addEventListener("DOMContentLoaded", captureReferralCode);