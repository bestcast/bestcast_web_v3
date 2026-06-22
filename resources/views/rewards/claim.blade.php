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

            <div class="form-row">
                <div class="form-group">
                    <label>Door No</label>
                    <input type="text" id="doorNo" 
                        value="{{ $claim->door_no ?? '' }}"
                        placeholder="Door / House No" />
                    <div class="error" id="doorNoError"></div>
                </div>

                <div class="form-group">
                    <label>Street Name</label>
                    <input type="text" id="streetName" 
                        value="{{ $claim->street_name ?? '' }}"
                        placeholder="Street name" />
                    <div class="error" id="streetNameError"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Country</label>
                    <select id="country"><option value="">Select Country</option></select>
                    <div class="error" id="countryError"></div>
                </div>

                <div class="form-group">
                    <label>State</label>
                    <select id="state"><option value="">Select State</option></select>
                    <div class="error" id="stateError"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>City</label>
                    <select id="city"><option value="">Select City</option></select>
                    <div class="error" id="cityError"></div>
                </div>
                 <div class="form-group">
                    <label>Pincode</label>
                    <input type="text" id="pinCode"
                        value="{{ $claim->pin_code ?? '' }}"
                        placeholder="Pincode" />
                    <div class="error" id="pinCodeError"></div>
                </div>
            </div>

            <div class="form-group full-width">
                <label>Mobile Number</label>
                <input type="text" id="mobile_no" 
                    value="{{ $claim->mobile_no ?? '' }}"
                    placeholder="Enter your mobile number" />
                <div class="error" id="mobileNoError"></div>
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
const errorMap = {
    full_name: "fullNameError",
    door_no: "doorNoError",
    street_name: "streetNameError",
    country: "countryError",
    state: "stateError",
    city: "cityError",
    pin_code: "pinCodeError",
    mobile_no: "mobileNoError"

};
const SAVED_COUNTRY = "{{ $claim->country ?? '' }}";
const SAVED_STATE   = "{{ $claim->state ?? '' }}";
const SAVED_CITY    = "{{ $claim->city ?? '' }}";

document.getElementById("claimForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Remove old errors
    document.querySelectorAll(".error").forEach(e => e.textContent = "");

    let claimId = document.getElementById("claimId").value;

    let method = claimId ? "PUT" : "POST";
    let url = claimId ? `/reward-claim/${claimId}` : `/reward-claim`;

    // Collect data
    const formData = {
        full_name: document.getElementById("fullName").value.trim(),
        door_no: document.getElementById("doorNo").value.trim(),
        street_name: document.getElementById("streetName").value.trim(),
        country: document.getElementById("country").value,
        state: document.getElementById("state").value,
        city: document.getElementById("city").value,
        pin_code: document.getElementById("pinCode").value.trim(),
        mobile_no: document.getElementById("mobile_no").value.trim()
    };

    const encrypted = encryptPayload(formData);

    fetch(url, {
        method: method,
        credentials: "include",
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

        if (payload.errors && typeof payload.errors === "object") {

            Object.keys(payload.errors).forEach(field => {
                const errorDivId = errorMap[field];

                if (errorDivId) {
                    const msg = payload.errors[field][0];
                    document.getElementById(errorDivId).textContent = msg;
                }
            });

            return; // stop submit
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
let countryCodeMap = {}; // name → ISO2

document.addEventListener("DOMContentLoaded", function () {

    const countrySelect = document.getElementById("country");

    fetch("https://restcountries.com/v3.1/all?fields=name,cca2")
        .then(res => res.json())
        .then(countries => {

            countries.sort((a, b) =>
                a.name.common.localeCompare(b.name.common)
            );

            countries.forEach(c => {
                const name = c.name.common;
                const iso2 = c.cca2;

                countryCodeMap[name] = iso2;

                const option = document.createElement("option");
                option.value = name;
                option.textContent = name;
                countrySelect.appendChild(option);
            });

            // Edit mode auto-select
            if (SAVED_COUNTRY) {
                countrySelect.value = SAVED_COUNTRY;
                countrySelect.dispatchEvent(new Event("change"));
            }

        })
        .catch(err => console.error("Country load failed:", err));
});
document.getElementById("country").addEventListener("change", function () {

    const countryName = this.value;
    const stateSelect = document.getElementById("state");

    stateSelect.innerHTML = '<option value="">Loading states...</option>';

    if (!countryName) {
        stateSelect.innerHTML = '<option value="">Select State</option>';
        return;
    }

    fetch("https://countriesnow.space/api/v0.1/countries/states", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ country: countryName })
    })
    .then(res => res.json())
    .then(data => {

        stateSelect.innerHTML = '<option value="">Select State</option>';

        if (!data.data || !data.data.states) return;

        data.data.states.forEach(s => {
            const opt = document.createElement("option");
            opt.value = s.name;
            opt.textContent = s.name;
            stateSelect.appendChild(opt);
        });
        if (SAVED_STATE) {
            stateSelect.value = SAVED_STATE;
            stateSelect.dispatchEvent(new Event("change"));
        }
    })
    .catch(err => {
        console.error("State load error:", err);
        stateSelect.innerHTML = '<option value="">Select State</option>';
    });
});

document.getElementById("state").addEventListener("change", function () {

    const country = document.getElementById("country").value;
    const state   = this.value;
    const citySel = document.getElementById("city");

    citySel.innerHTML = '<option value="">Loading cities...</option>';

    if (!country || !state) {
        citySel.innerHTML = '<option value="">Select City</option>';
        return;
    }

    fetch("https://countriesnow.space/api/v0.1/countries/state/cities", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            country: country,
            state: state
        })
    })
    .then(res => res.json())
    .then(data => {

        citySel.innerHTML = '<option value="">Select City</option>';

        if (!data.data || !Array.isArray(data.data)) return;

        data.data.forEach(city => {
            const opt = document.createElement("option");
            opt.value = city;
            opt.textContent = city;
            citySel.appendChild(opt);
        });

        // auto-select saved city (edit mode)
        if (SAVED_CITY) {
            citySel.value = SAVED_CITY;
        }

    })
    .catch(err => {
        console.error("City load error:", err);
        citySel.innerHTML = '<option value="">Select City</option>';
    });
});
</script>
</body>
</html>