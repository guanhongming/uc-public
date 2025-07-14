<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileField;
use App\Models\ProfileAnswer;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $fields = ProfileField::orderBy('label')->get();
        //dd(get_class(Auth::user()));
        $answers = $user->answers()
                         ->pluck('value', 'profile_field_id');

        return response()->json([
            'fields' => $fields,
            'answers' => $answers,
        ]);
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'answers' => 'required|array'
        ]);

        foreach ($validated['answers'] as $field_id => $value) {
            if (ProfileField::where('id', $field_id)->exists()) {
                ProfileAnswer::updateOrCreate(
                    ['user_id' => $user->id, 'profile_field_id' => $field_id],
                    ['value' => $value]
                );
            }
        }

        return response()->json(['message' => 'Profile updated successfully!']);
    }
}