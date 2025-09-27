<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class CheckSubscriberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $subscriberId = $request->route('subscriber'); // параметр из маршрута
        $subscriber = Subscriber::find($subscriberId);

        if (!$subscriber) {
            return response()->json(['error' => 'Subscriber not found'], 404);
        }

        if ($subscriber->status !== 'active') {
            return response()->json(['error' => 'Subscriber is inactive'], 403);
        }

        $request->attributes->set('subscriber', $subscriber);

        return $next($request);
    }
}
