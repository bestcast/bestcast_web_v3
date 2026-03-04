<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RewardClaim;

class RewardController extends Controller
{
    public function store(Request $request)
    {
        $userId = auth()->id();

        // Prevent duplicate claim
        if (RewardClaim::where('user_id', $userId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a reward claim.'
            ], 409);
        }

        // Validate request directly
        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'door_no'     => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'country'     => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'pin_code'    => 'required|string|max:10',
            'mobile_no'   => 'required|string|max:20',
        ]);

        $claim = RewardClaim::create([
            'user_id' => $userId,
            ...$validated
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward claim submitted successfully!',
            'data'    => $claim
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $claim = RewardClaim::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'door_no'     => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'country'     => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'pin_code'    => 'required|string|max:10',
            'mobile_no'   => 'required|string|max:20',
        ]);

        $claim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reward claim updated successfully!',
            'data'    => $claim
        ]);
    }

}
