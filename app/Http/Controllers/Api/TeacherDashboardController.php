<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ClassGroup;
use App\Models\ProfileAnswer;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $teacher = Auth::user();

        $classGroups = $teacher->classGroups()->with([
            'users' => function ($query) {
                $query->where('role', 'student');
            },
            'users.answers',
            'users.answers.profileField'
        ])->get();

        return response()->json($classGroups);
    }

    public function filterStudents(Request $request)
    {
        $teacher = Auth::user();

        $query = User::where('role', 'student');

        $teacherClassGroupIds = $teacher->classGroups->pluck('id');

        $query->whereHas('classGroups', function ($q) use ($teacherClassGroupIds) {
            $q->whereIn('class_groups.id', $teacherClassGroupIds);
        });

        $yearGroup = $request->input('year_group');
        $academicClass = $request->input('academic_class');
        $studentName = $request->input('student_name');

        if ($yearGroup) {
            $query->whereHas('classGroups', function ($q) use ($yearGroup) {
                $q->where('name', $yearGroup);
            });
        }

        if ($academicClass) {
             $query->whereHas('classGroups', function ($q) use ($academicClass) {
                 $q->where('name', $academicClass);
             });
        }

        if ($studentName) {
            $query->where('name', 'LIKE', '%' . $studentName . '%');
        }

        $students = $query->with(['answers.profileField'])->get();

        return response()->json($students);
    }
}