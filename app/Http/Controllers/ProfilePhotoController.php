<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class ProfilePhotoController extends Controller
{
    public function update(Request $request, int $user_id)
    {

        $request->validate([
            'profile_picture' => 'required|file|max:5000|mimes:jpg,png,gif,jpeg,webp'
        ]);

        // lets find out how big our profile image is
        $image_size = getimagesize($request->profile_picture);

        // if its larger than 450px in x or y, let's return an appropriate error
        if ($image_size[0] > 450 || $image_size[1] > 450 && ! $request->expectsJson() ) {
            
            $errors['profile_picture'] = 'Please ensure your picture no larger than 450px in height or width';
            return redirect()->route('dashboard')->withErrors($errors);

        } else if ( $image_size[0] > 450 || $image_size[1] > 450 &&  $request->expectsJson() ) {

            return [
                'status' => 'failed',
                'message' => 'Please ensure your picture no larger than 450px in height or width'
            ];

        }

        $user = User::where('id', $user_id)->firstOrFail();

        // if we already have a profile picture in storage, let's delete it
        if ($user->profile_picture) {

            Storage::delete('/public/' . $user->profile_picture);

        }
 
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        // lets update the path to the profile picture
        $user->profile_picture = $path;

        $user->save();

        if ( ! $request->expectsJson() ) {
            return redirect()->route('dashboard');
        } else {
            return [
                'status' => 'success',
                'data' => $user
            ];
        }
    }
}
