<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;

class StudentsController extends Controller
{
    // ===================== INDEX =====================
    public function index()
    {
        $students = Student::latest()->get();

        return request()->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Students loaded',
                'data'    => $students
            ])
            : view('students.index', compact('students'));
    }

    // ===================== CREATE =====================
    public function create()
    {
        return view('students.create');
    }

    // ===================== STORE =====================
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|unique:students,student_id|max:20',
                'name'       => 'required|max:255',
                'course'     => 'required|max:50',
                'year_level' => 'required|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student Already Existed!',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $student = Student::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Student added successfully!',
                'data'    => $student
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    // ===================== EDIT =====================
    public function edit(Student $student)
    {
        return request()->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Student loaded',
                'data'    => $student
            ])
            : view('students.edit', compact('student'));
    }

    // ===================== UPDATE =====================
    public function update(Request $request, Student $student)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|unique:students,student_id,' . $student->id,
                'name'       => 'required|max:255',
                'course'     => 'required|max:50',
                'year_level' => 'required|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Update Failed',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $student->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!',
                'data'    => $student
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    // ===================== DELETE =====================
    public function destroy(Student $student)
    {
        try {
            if ($student->borrowTransactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete student with borrow records'
                ], 400);
            }

            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }
}