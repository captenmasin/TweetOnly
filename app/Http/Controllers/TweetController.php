<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetRequest;
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

    public function tweets(Request $request)
    {
        return Auth::user()->getTweets();
    }
}
