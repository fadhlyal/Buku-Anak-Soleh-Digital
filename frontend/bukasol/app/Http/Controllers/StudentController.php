<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Store a newly created user and student in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [ 
            // User data validation
            'name'        => 'required|string|max:255',

            // Student data validation
            'nisn'        => 'required|string|unique:students,nisn|max:20',
            'parent_name' => 'required|string|max:255',
            'teacher_id'  => 'required|exists:teachers,id',
        ] );

        $countStudent = Student::count() + 1;
        $username = strtolower(explode(' ', $validatedData['name'])[0]).$countStudent;

        Validator::make(
            ['username' => $username],
            ['username' => 'required|string|unique:users,username|max:50']
        )->validate();

        $letters = strtolower(explode(' ', $validatedData['name'])[0]);
        $numbers = substr($validatedData['nisn'], -4);
        $password = $letters . $numbers;
        
        $teacher = Teacher::find($validatedData[ 'teacher_id' ]);
        $className = $teacher->class_name;

        // Create the User first
        $user = User::create ( [ 
            'name'     => $validatedData[ 'name' ],
            'username' => $username,
            'password' => Hash::make ( $password ),
            'role'     => "Student",
        ] );

        // Then, create the Student using the User's ID
        Student::create ( [ 
            'user_id'     => $user->id,
            'nisn'        => $validatedData[ 'nisn' ],
            'class_name'  => $className,
            'parent_name' => $validatedData[ 'parent_name' ],
            'parent_code' => strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4)),
            'teacher_id'  => $validatedData[ 'teacher_id' ],
        ] );

        return redirect()->route('admin.student-table.index')
            ->with('success', 'Sukses Menambahkan Data Siswa Baru');
    }

    /**
     * Display the specified student details.
     */
    public function show ( Student $student )
    {
        $student->load ( 'user', 'teacher.user', 'juz', 'muhasabahReports', 'notes', 'prayerGrades', 'prayerRecitationGrades', 'readActivities', 'violationReports' );
        return response ()->json ( $student );
    }

    /**
     * Update the specified student and user in storage.
     */
    public function update ( Request $request, Student $student )
    {
        $validatedData = $request->validate ( [ 
            // User data validation
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:50|unique:users,username,' . $student->user->id,
            'password'    => 'nullable|string|min:8|confirmed',

            // Student data validation
            'nisn'        => 'required|string|max:20|unique:students,nisn,' . $student->id,
            'class_name'  => 'required|string|max:50',
            'parent_name' => 'required|string|max:255',
            'parent_code' => 'required|string|max:50',
        ] );

        // Update User data
        $student->user->update ( [ 
            'name'     => $validatedData[ 'name' ],
            'username' => $validatedData[ 'username' ],
            'password' => $validatedData[ 'password' ] ? Hash::make ( $validatedData[ 'password' ] ) : $student->user->password,
        ] );

        // Update Student data
        $student->update ( [ 
            'nisn'        => $validatedData[ 'nisn' ],
            'class_name'  => $validatedData[ 'class_name' ],
            'parent_name' => $validatedData[ 'parent_name' ],
            'parent_code' => $validatedData[ 'parent_code' ],
        ] );

        if ( $request->ajax () )
        {
            return response ()->json ( [ 'success' => 'Sukses Mengubah Data Siswa' ] );
        }

        return response ()->json ( [ 'error' => 'Gagal Mengubah Data Siswa' ] );
    }

    /**
     * Remove the specified student and associated user from storage.
     */
    public function destroy ( Request $request, Student $student )
    {
        // Delete the related user, if it exists
        if ( $student->user )
        {
            $student->juz()->delete();
            $student->muhasabahReports()->delete();
            $student->notes()->delete();
            $student->prayerGrades()->delete();
            $student->prayerRecitationGrades()->delete();
            $student->readActivities()->delete();
            $student->violationReports()->delete();

            $student->user->delete ();
        }

        // Delete the student
        $student->delete ();

        // Refresh the student instance to check if it still exists in the database
        $studentExists = Student::where ( 'id', $student->id )->exists ();
        $userExists    = $student->user ? User::where ( 'id', $student->user->id )->exists () : false;

        // Check if both student and user no longer exist
        if ( ! $studentExists && ! $userExists )
        {
            return response ()->json ( [ 'success' => 'Data Siswa Berhasil Dihapus' ] );
        }

        return response ()->json ( [ 'error' => 'Data Siswa Gagal Dihapus' ] );
    }
}
