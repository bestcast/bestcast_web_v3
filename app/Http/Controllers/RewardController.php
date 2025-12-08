<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RewardClaim;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QuizCryptoHelper;
use Log;

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
            $encrypted = QuizCryptoHelper::encryptPayload([
                'success' => false,
                'message' => 'You have already submitted a reward claim.'
            ]);

            return response()->json($encrypted, 409);
        }

        $raw = $request->getContent();
        $payload = json_decode($raw, true);
        $decrypted = QuizCryptoHelper::decryptPayload($payload['data'], $payload['iv']);

        // Validation
        $validator = Validator::make($decrypted, [
            'full_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:30',
            'ifsc' => 'required|string|max:20',
            'branch' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'upi' => 'nullable|string|max:50',
        ]);

        // If validation fails — SEND ENCRYPTED ERROR
        if ($validator->fails()) {
            $encrypted = QuizCryptoHelper::encryptPayload([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()->toArray()
            ]);

            return response()->json($encrypted, 422);
        }
        $validated = $validator->validated();
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

        $encryptedResponse = QuizCryptoHelper::encryptPayload([
            'success' => true,
            'message' => 'Reward claim submitted successfully!',
            'data'    => $claim
        ]);

        return response()->json($encryptedResponse);
    }

    public function update(Request $request, $id)
    {
        $claim = RewardClaim::findOrFail($id);

        $raw = $request->getContent();
        $payload = json_decode($raw, true);
        $decrypted = QuizCryptoHelper::decryptPayload($payload['data'], $payload['iv']);

        $validator = Validator::make($decrypted, [
            'full_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:30',
            'ifsc' => 'required|string|max:20',
            'branch' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'upi' => 'nullable|string|max:50',
        ]);

        // If validation fails — SEND ENCRYPTED ERROR
        if ($validator->fails()) {
            $encrypted = QuizCryptoHelper::encryptPayload([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()->toArray()
            ]);

            return response()->json($encrypted, 422);
        }

        $validated = $validator->validated();
        $claim->update($validated);

        $encryptedResponse = QuizCryptoHelper::encryptPayload([
            'success' => true,
            'message' => 'Reward claim updated successfully!',
            'data' => $claim
        ]);
        return response()->json($encryptedResponse);
    }

}
