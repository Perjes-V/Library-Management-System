<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentsController extends Controller
{
    // ===================== INDEX =====================
    public function index(Request $request)
    {
        $students = Student::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        }

        return view('students.index', compact('students'));
    }

    // ===================== CREATE =====================
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('students.partials.create'); // optional partial for AJAX
        }

        return view('students.create');
    }

    // ===================== STORE =====================
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:students,student_id|max:20',
            'name'       => 'required|unique:students,name|max:255',
            'course'     => 'required|max:50',
            'year_level' => 'required|integer|min:1|max:5',
        ]);

        try {
            $student = Student::create([
                'student_id' => $request->student_id,
                'name'       => $request->name,
                'course'     => $request->course,
                'year_level' => $request->year_level,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student added successfully!',
                    'data'    => $student
                ]);
            }

            return redirect()->route('students.index')
                             ->with('success', 'Student added successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add student. ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to add student.');
        }
    }

    // ===================== EDIT =====================
    public function edit(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($request->ajax()) {
            return view('students.partials.edit', compact('student'));
        }

        return view('students.edit', compact('student'));
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $id . '|max:20',
            'name'       => 'required|unique:students,name,' . $id . '|max:255',
            'course'     => 'required|max:50',
            'year_level' => 'required|integer|min:1|max:5',
        ]);

        try {
            $student->update([
                'student_id' => $request->student_id,
                'name'       => $request->name,
                'course'     => $request->course,
                'year_level' => $request->year_level,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student updated successfully!',
                    'data'    => $student
                ]);
            }

            return redirect()->route('students.index')
                             ->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update student. ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update student.');
        }
    }

    // ===================== DELETE =====================
    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Optional: Prevent deletion if student has borrow records
        if ($student->borrowTransactions()->exists()) {
            $message = 'Cannot delete student with borrow records!';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->route('students.index')->with('error', $message);
        }

        try {
            $student->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Student deleted successfully!']);
            }

            return redirect()->route('students.index')
                             ->with('success', 'Student deleted successfully!');
        } catch (\Exception $e) {
            $message = 'Failed to delete student. ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }
}