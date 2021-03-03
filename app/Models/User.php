<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Thujohn\Twitter\Facades\Twitter;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function setTwitterTokens()
    {
        $token = decrypt($this->access_token);
        $secret = decrypt($this->access_token_secret);
        Twitter::reconfig(['token' => $token, 'secret' => $secret]);
    }

    public function getTweets()
    {
        $this->setTwitterTokens();

        return Twitter::getUserTimeline(['count' => 20, 'format' => 'json']);
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
            return Twitter::postTweet(['status' => $content, 'format' => 'json']);
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

            return Twitter::postTweet(['status' => $content, 'media_ids' => $allImages, 'format' => 'json']);
        }
    }
}
