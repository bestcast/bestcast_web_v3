<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claim Your Winning Prize</title>
    <link rel="stylesheet" href="{{ url('/') }}/css/reward.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/crypto-js.min-new.js') }}"></script>
</head>

<body>

    <div class="container">
        <h2>Claim Your Winning Prize</h2>
        @if($claim)
            <div class = "updateform">
                You have already submitted your reward claim.  
                If you want to change any details, please update the form below.
            </div>
        @endif

        <form id="claimForm" novalidate>

            <input type="hidden" id="claimId" value="{{ $claim->id ?? '' }}">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="fullName" 
                    value="{{ $claim->full_name ?? '' }}"
                    placeholder="Enter Full Name" />
                <div class="error" id="fullNameError"></div>
            </div>

            <div class="form-group">
                <label>Bank Name</label>
                <input type="text" id="bankName" 
                    value="{{ $claim->bank_name ?? '' }}"
                    placeholder="Enter Bank Name" />
                <div class="error" id="bankNameError"></div>
            </div>

            <div class="form-group">
                <label>Account No</label>
                <input type="text" id="accountNo" 
                    value="{{ $claim->account_no ?? '' }}"
                    placeholder="Enter Account Number" />
                <div class="error" id="accountNoError"></div>
            </div>

            <div class="form-group">
                <label>IFSC Code</label>
                <input type="text" id="ifsc" 
                    value="{{ $claim->ifsc ?? '' }}"
                    placeholder="Enter IFSC" />
                <div class="error" id="ifscError"></div>
            </div>

            <div class="form-group">
                <label>Bank Branch</label>
                <input type="text" id="branch" 
                    value="{{ $claim->branch ?? '' }}"
                    placeholder="Enter Branch Name" />
                <div class="error" id="branchError"></div>
            </div>

            <div class="form-group full-width">
                <label>Mobile Number</label>
                <input type="text" id="mobile_no" 
                    value="{{ $claim->mobile_no ?? '' }}"
                    placeholder="Enter your mobile number" />
                <div class="error" id="mobileNoError"></div>
            </div>

            <div class="form-group full-width">
                <label>UPI ID</label>
                <input type="text" id="upi" 
                    value="{{ $claim->upi ?? '' }}"
                    placeholder="Enter your UPI ID" />
                <div class="error" id="upiError"></div>
            </div>

            <div class="button-container full-width">
                <button type="submit">
                    {{ $claim ? 'Update' : 'Submit' }}
                </button>
            </div>

        </form>
    </div>

<script>
const APP_AES_KEY = CryptoJS.enc.Base64.parse("{{ env('QUIZ_SECRET_KEY') }}");
function encryptPayload(data) {
    const iv = CryptoJS.lib.WordArray.random(16);

    const encrypted = CryptoJS.AES.encrypt(
        JSON.stringify(data),
        APP_AES_KEY,
        { iv: iv }
    );

    return {
        iv: CryptoJS.enc.Base64.stringify(iv),
        data: encrypted.ciphertext.toString(CryptoJS.enc.Base64)
    };
}
function decryptResponse(encrypted) {
    try {
        const iv  = CryptoJS.enc.Base64.parse(encrypted.iv);
        const cipher = CryptoJS.lib.CipherParams.create({
            ciphertext: CryptoJS.enc.Base64.parse(encrypted.data)
        });

        const decrypted = CryptoJS.AES.decrypt(cipher, APP_AES_KEY, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        });

        return JSON.parse(decrypted.toString(CryptoJS.enc.Utf8));
    } catch (err) {
        console.error("DECRYPT ERROR:", err);
        return null;
    }
}
document.getElementById("claimForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Remove old errors
    document.querySelectorAll(".error").forEach(e => e.textContent = "");

    let claimId = document.getElementById("claimId").value;

    let method = claimId ? "PUT" : "POST";
    let url = claimId ? `/api/reward-claim/${claimId}` : `/api/reward-claim`;

    // Collect data
    const formData = {
        full_name: document.getElementById("fullName").value.trim(),
        bank_name: document.getElementById("bankName").value.trim(),
        account_no: document.getElementById("accountNo").value.trim(),
        ifsc: document.getElementById("ifsc").value.trim(),
        branch: document.getElementById("branch").value.trim(),
        mobile_no: document.getElementById("mobile_no").value.trim(),
        upi: document.getElementById("upi").value.trim()
    };

    const encrypted = encryptPayload(formData);

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(encrypted)
    })
    .then(async (r) => {
        // read raw text for best debugging (works for both JSON and encrypted JSON)
        const rawText = await r.text();

        // try parse JSON
        let parsed;
        try { parsed = JSON.parse(rawText); }
        catch (e) { 
            console.error("Failed to parse JSON from server:", e, rawText);
            throw new Error("Invalid server response");
        }

        return { status: r.status, body: parsed };
    })
    .then(res => {
        // If server returned plain 409 conflict JSON (not encrypted): use it directly
        if (res.status === 409) {
            // decrypted or plain, show server message if any
            const msg = (res.body && (res.body.message || res.body.error)) ? (res.body.message || res.body.error) : "Conflict";
            return;
        }

        // Determine if the body looks encrypted (has iv + data)
        const body = res.body;
        const isEncrypted = body && typeof body.iv === 'string' && typeof body.data === 'string';

        let payload = null;

        if (isEncrypted) {
            try {
                payload = decryptResponse(body);
            } catch (err) {
                console.error("Decrypt failed:", err);
                return;
            }
        } else {
            // not encrypted — maybe server returned validation errors or plain success
            payload = body;
        }

        // If payload is not an object, stop
        if (!payload || typeof payload !== 'object') {
            console.error("Invalid payload after decrypt/parse:", payload);
            return;
        }

        // If server returned validation errors format (Laravel), show messages
        if (payload.errors && typeof payload.errors === 'object') {
            // pick first error message
            const firstField = Object.keys(payload.errors)[0];
            const firstMsg = payload.errors[firstField][0];
            return;
        }

        // If payload explicitly has success === false, show the server message if present
        if (payload.success === false) {
            const m = payload.message || payload.error || "Operation failed";
            return;
        }

        // SUCCESS case: payload.success true or no success flag but we have data
        if (payload.success === true || payload.data || payload.message) {
            Swal.fire({
                title: claimId ? "Updated Successfully!" : "Submitted Successfully!",
                text: payload.message ? payload.message + "\n\nPlease wait some time, you will receive your reward shortly." :
                      (claimId ? "Your reward details have been updated." : "Your reward claim has been submitted.") + "\n\nPlease wait some time, you will receive your reward shortly.",
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6",
                allowOutsideClick: false,
                customClass: {
                    popup: 'custom-swal-popup',
                    confirmButton: 'custom-swal-confirm'
                },
            }).then(() => {
                window.location.href = "/browse";
            });
            return;
        }

        // fallback
        console.warn("Unhandled payload:", payload);

    })
    .catch(err => {
        console.error("Fetch/processing error:", err);
    });

});
</script>
</body>
</html>
