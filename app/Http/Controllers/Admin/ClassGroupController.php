<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use Illuminate\Http\Request;

class ClassGroupController extends Controller
{
    public function index() {
      
        return ClassGroup::with('users')->get();
    }

    public function update(Request $request, ClassGroup $classGroup) {
        $validated = $request->validate([
            'userIds' => 'required|array',
            'userIds.*' => 'exists:users,id'
        ]);

        $classGroup->users()->sync($validated['userIds']);

        return response()->json($classGroup->load('users'));
    }
}