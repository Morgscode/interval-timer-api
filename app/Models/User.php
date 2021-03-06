<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Http\Request;

use App\Models\Session;
use App\Models\Interval;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birth_date',
        'age',
        'height',
        'weight',
        'profile_picture',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function intervals()
    {
        return $this->hasMany(Interval::class);
    }

    public function sessoions()
    {
        return $this->hasMany(Session::class);
    }

    public function validateNewUser(Request $request)
    {
        return $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
    }

    public function validateUpdatedUser(Request $request)
    {
        return $request->validate([
            'name' => 'nullable|string|max:50',
            'email' => 'nullable|string|unique:users|email',
            'gender' => 'nullable|string|max:6',
            'birth_date' => 'nullable|date',
            'height' => 'nullable|numeric|max:500',
            'weight' => 'nullable|numeric|max:750'
        ]);
    }
}
