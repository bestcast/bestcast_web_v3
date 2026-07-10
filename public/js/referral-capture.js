// referral-capture.js
const REF_KEY = "bestcastReferralCode";
const REF_TIME_KEY = "bestcastReferralCapturedAt";
const EXPIRY_DAYS = 30;

function captureReferralCode() {
  const params = new URLSearchParams(window.location.search);
  const refCode = params.get("ref");

  if (refCode && refCode.trim()) {
    localStorage.setItem(REF_KEY, refCode.trim());
    localStorage.setItem(REF_TIME_KEY, Date.now().toString());
  }
}

function getStoredReferralCode() {
  const code = localStorage.getItem(REF_KEY);
  const capturedAt = parseInt(localStorage.getItem(REF_TIME_KEY) || "0", 10);

  if (!code || !capturedAt) return null;

  const ageMs = Date.now() - capturedAt;
  const expiryMs = EXPIRY_DAYS * 24 * 60 * 60 * 1000;

  if (ageMs > expiryMs) {
    localStorage.removeItem(REF_KEY);
    localStorage.removeItem(REF_TIME_KEY);
    return null;
  }
  return code;
}

// Run capture on every page load
document.addEventListener("DOMContentLoaded", captureReferralCode);