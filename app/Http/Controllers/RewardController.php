<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RewardClaim;
use Auth;

class RewardController extends Controller
{
    public function reward($user_id)
    {
        // Find claim for user (if exists)
        $claim = RewardClaim::where('user_id', $user_id)->first();

        return view('rewards.claim', compact('claim'));
    }
    public function store(Request $request)
    {
        if (RewardClaim::where('user_id', auth()->id())->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a reward claim.'
            ], 409);
        }
        // Validation
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:30',
            'ifsc' => 'required|string|max:20',
            'branch' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'upi' => 'nullable|string|max:50',
        ]);

        // Save
        $claim = RewardClaim::create([
            'user_id'   => auth()->id(),
            'full_name' => $validated['full_name'],
            'bank_name' => $validated['bank_name'],
            'account_no'=> $validated['account_no'],
            'ifsc'      => $validated['ifsc'],
            'branch'    => $validated['branch'],
            'mobile_no' => $validated['mobile_no'],
            'upi'       => $validated['upi'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward claim submitted successfully!',
            'data'    => $claim
        ]);
    }

    public function update(Request $request, $id)
    {
        $claim = RewardClaim::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:30',
            'ifsc' => 'required|string|max:20',
            'branch' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'upi' => 'nullable|string|max:50',
        ]);

        $claim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reward claim updated successfully!',
            'data' => $claim
        ]);
    }

}
