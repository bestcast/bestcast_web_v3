<?php

namespace App\Http\Controllers\Traits;

use App\Helpers\QuizCryptoHelper;
use Illuminate\Http\Request;

trait DecryptsQuizPayload
{
    protected function decryptPayloadFromRequest(Request $request): array
    {
        $request->validate([
            'payload.iv'   => 'required|string',
            'payload.data' => 'required|string',
        ]);

        $data = QuizCryptoHelper::decryptPayload(
            $request->payload['data'],
            $request->payload['iv']
        );

        if (!$data) {
            abort(
                response()->json(['error' => 'Invalid encrypted request'], 400)
            );
        }

        return $data;
    }
}
