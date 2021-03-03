<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function tweet(TweetRequest $request): RedirectResponse
    {
        $images = $request->allFiles()['image'] ?? null;
        Auth::user()->tweet($request->tweet_content, $images);

        return redirect()->back()->with([
            'tweeted'         => true,
            'tweeted_content' => $request->tweet_content,
        ]);
    }

    public function singleTweet(Request $request, int $tweet): JsonResponse
    {
        return response()->json(['data' => Auth::user()->getTweet($tweet)]);
    }

    public function tweets(Request $request): JsonResponse
    {
        return response()->json(json_decode(Auth::user()->getTweets()));
    }
}
