<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
       
        return User::where('id', '!=', Auth::user()->id)->get();
    }

    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'role' => 'required|in:student,teacher,admin',
        ]);
        $user->update($validated);
        return response()->json($user);
    }

    public function destroy(User $user) {
        $user->delete();
        return response()->noContent();
    }
}