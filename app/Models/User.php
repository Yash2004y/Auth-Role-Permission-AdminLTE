<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, CommonModelTrait;

    protected static $folder = "User Images";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'add_by',
        'image'
    ];

     protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->update(['add_by' => auth()?->user()?->id]);
        });
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getImageAttribute($fileName)
    {
        return getFileFullPath($fileName, User::$folder);
    }
    public function changeUserImage(UploadedFile $file)
    {
        // return $file->getClientOriginalName();
        // return "hi";
        $this->deleteImage();
        $fileInfo = uploadFile($file, 'user_image_' . rand(1, 200) . $this->id, User::$folder);
        $this->update(['image' => $fileInfo["filename"]]);
    }
    public function deleteImage()
    {
        if (!empty($this->getRawOriginal("image"))) {
            deleteFile($this->getRawOriginal("image"), User::$folder);
        }
    }
}
