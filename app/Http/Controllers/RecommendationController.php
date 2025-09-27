<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // псевдонимированный ID для кэша
        $anonId = hash_hmac('sha256', $user->id, config('app.key'));
        
        $recommendations = Cache::get("reco:{$anonId}");

        if (!$recommendations) {
            return response()->json([
                'status' => 'empty',
                'message' => 'Рекомендации ещё не готовы'
            ], 204);
        }

        return response()->json([
            'status' => 'ok',
            'recommendations' => $recommendations
        ]);
    }
}
