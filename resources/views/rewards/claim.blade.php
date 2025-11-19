<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claim Your Winning Prize</title>
    <link rel="stylesheet" href="{{ url('/') }}/css/reward.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(r => r.json().then(data => ({ status: r.status, body: data })))
    .then(res => {

        if (res.status === 409) {
            alert(res.body.message);
            return;
        }

        if (res.body.success) {
            //alert(claimId ? "Updated Successfully!" : "Submitted Successfully!");
            Swal.fire({
                title: claimId ? "Updated Successfully!" : "Submitted Successfully!",
                text: (claimId 
                    ? "Your reward details have been updated."
                    : "Your reward claim has been submitted."
                  ) + "\n\nPlease wait some time, you will receive your reward shortly.",
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
            if (!claimId) location.reload();
        } else {
            alert("Something went wrong!");
        }

    })
    .catch(err => console.error(err));
});
</script>

</body>
</html>
