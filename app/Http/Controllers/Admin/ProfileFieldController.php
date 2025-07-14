<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileField;
use Illuminate\Http\Request;

class ProfileFieldController extends Controller
{

    public function index()
    {

        return response()->json(ProfileField::orderBy('created_at')->get());
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'label' => 'required|string|max:255|unique:profile_fields,label',
            'field_type' => 'required|string|in:text,textarea',
        ]);

        $profileField = ProfileField::create($validated);


        return response()->json($profileField, 201);
    }


    public function destroy(ProfileField $profileField)
    {

        $profileField->delete();

        return response()->noContent();
    }
}