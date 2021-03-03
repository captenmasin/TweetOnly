<?php

namespace App\Models;

use App\Enums\Time;
use App\Enums\UserCacheKeys;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_photo_path', 'provider_id', 'provider', 'access_token', 'access_token_secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setTwitterTokens()
    {
        $token = decrypt($this->access_token);
        $secret = decrypt($this->access_token_secret);
        Twitter::reconfig(['token' => $token, 'secret' => $secret]);
    }

    public function getTweets()
    {
        return Cache::remember('user_'.$this->id.':tweets', now()->addHour(), function () {
            $this->setTwitterTokens();

            return Twitter::getUserTimeline(['count' => 20, 'format' => 'json']);
        });
    }

    public function getTweet(int $tweet)
    {
        $this->setTwitterTokens();

        return Twitter::getTweet($tweet);
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
