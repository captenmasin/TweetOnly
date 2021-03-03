<?php

namespace App\Models;

use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Thujohn\Twitter\Facades\Twitter;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasSettingsTable;
    use HasRoles;

    public $defaultSettings = [
        'darkMode' => false,
    ];

    protected $fillable = [
        'name', 'email', 'password', 'profile_photo_path', 'provider_id', 'provider', 'access_token', 'access_token_secret',
    ];

    protected $hidden = [
        'password',
        'access_token',
        'access_token_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setTwitterTokens()
    {
        $token = decrypt($this->access_token);
        $secret = decrypt($this->access_token_secret);
        Twitter::reconfig(['token' => $token, 'secret' => $secret]);
    }

    public function getTweets($count = 20)
    {
        return Cache::remember('user_'.$this->id.':tweets', now()->addHour(), function () use ($count) {
            $this->setTwitterTokens();

            return json_decode(Twitter::getUserTimeline(['count' => $count, 'format' => 'json']));
        });
    }

    public function getTweet(int $tweet)
    {
        $this->setTwitterTokens();

        return Twitter::getTweet($tweet);
    }

    public function getFollowers($count = 20)
    {
        return Cache::remember('user_'.$this->id.':'.$count.'_followers', now()->addHour(), function () use ($count) {
            $this->setTwitterTokens();

            return Twitter::getFollowers(['count' => $count]);
        });
    }

    public function getFollowing($count = 20)
    {
        return Cache::remember('user_'.$this->id.':'.$count.'_followers', now()->addHour(), function () use ($count) {
            $this->setTwitterTokens();

            return Twitter::getFriends(['count' => $count]);
        });
    }

    public function tweet($content, $images = null)
    {
        $this->setTwitterTokens();

        if (is_null($images)) {
            Twitter::postTweet(['status' => $content, 'format' => 'json']);
        } else {
            $allImages = [];
            foreach ($images as $image) {
                $uploadedImage = Storage::put('images', $image);
                try {
                    $getUploadedImage = Storage::get($uploadedImage);
                } catch (FileNotFoundException $e) {
                }
                $uploaded_media = Twitter::uploadMedia(['media' => $getUploadedImage]);
                $allImages[] = $uploaded_media->media_id_string;
            }

            Twitter::postTweet(['status' => $content, 'media_ids' => $allImages, 'format' => 'json']);
        }

        Cache::forget('user_'.$this->id.':tweets');
    }
}
