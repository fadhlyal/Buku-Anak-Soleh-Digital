<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Store a newly created user and teacher in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [ 
            // User data validation
            'name'       => 'required|string|max:255',

            // Teacher data validation
            'nip'        => 'required|string|unique:teachers,nip|max:20',
            'class_name' => 'required|string|max:50',
        ] );

        $countTeacher = Teacher::count() + 1;
        $username = "guru_".strtolower(explode(' ', $validatedData['name'])[0]).$countTeacher;

        Validator::make(
            ['username' => $username],
            ['username' => 'required|string|unique:users,username|max:50']
        )->validate();

        $letters = strtolower(explode(' ', $validatedData['name'])[0]);
        $numbers = substr($validatedData['nip'], -4);
        $password = $letters . $numbers;

        // Create the User first
        $user = User::create ( [ 
            'name'     => $validatedData[ 'name' ],
            'username' => $username,
            'password' => Hash::make ( $password ),
            'role'     => "Teacher",
        ] );

        // Then, create the Teacher using the User's ID
        Teacher::create ( [ 
            'user_id'    => $user->id,
            'nip'        => $validatedData[ 'nip' ],
            'class_name' => $validatedData[ 'class_name' ],
        ] );

        return redirect()->route('admin.teacher-table.index')
            ->with('success', 'Sukses Menambahkan Data Guru Baru.');
    }

    /**
     * Display the specified teacher details.
     */
    public function show ( Teacher $teacher )
    {
        $teacher->load ( 'user', 'students' );
        return response ()->json ( $teacher );
    }

    /**
     * Update the specified teacher and user in storage.
     */
    public function update ( Request $request, Teacher $teacher )
    {
        $validatedData = $request->validate ( [ 
            // User data validation
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:50|unique:users,username,' . $teacher->user->id,
            'password'   => 'nullable|string|min:8|confirmed',

            // Teacher data validation
            'nip'        => 'required|string|max:20|unique:teachers,nip,' . $teacher->id,
            'class_name' => 'required|string|max:50',
        ] );

        // Update User data
        $teacher->user->update ( [ 
            'name'     => $validatedData[ 'name' ],
            'username' => $validatedData[ 'username' ],
            'password' => $validatedData[ 'password' ] ? Hash::make ( $validatedData[ 'password' ] ) : $teacher->user->password,
        ] );

        // Update Teacher data
        $teacher->update ( [ 
            'nip'        => $validatedData[ 'nip' ],
            'class_name' => $validatedData[ 'class_name' ],
        ] );

        if ( $request->ajax () )
        {
            return response ()->json ( [ 'success' => 'Sukses Mengubah Data Guru.' ] );
        }

        return response ()->json ( [ 'error' => 'Gagal Mengubah Data Guru.' ] );
    }

    /**
     * Remove the specified teacher and associated user from storage.
     */
    public function destroy ( Request $request, Teacher $teacher )
    {
        // Delete the related user, if it exists

        foreach ($teacher->students as $student) {

            if ($student->user) {
                $student->juz()->delete();
                $student->muhasabahReports()->delete();
                $student->notes()->delete();
                $student->prayerGrades()->delete();
                $student->prayerRecitationGrades()->delete();
                $student->readActivities()->delete();
                $student->violationReports()->delete();

                $student->user->delete();
            }

            // Finally, delete the student
            $student->delete();
        }

        if ( $teacher->user )
        {
            $teacher->user->delete ();
        }

        // Delete the teacher
        $teacher->delete ();

        // Refresh the teacher instance to check if it still exists in the database
        $teacherExists = Teacher::where ( 'id', $teacher->id )->exists ();
        $userExists    = $teacher->user ? User::where ( 'id', $teacher->user->id )->exists () : false;

        // Check if both teacher and user no longer exist
        if ( ! $teacherExists && ! $userExists )
        {
            return response ()->json ( [ 'success' => 'Data Guru Berhasil Dihapus.' ] );
        }

        return response ()->json ( [ 'error' => 'Data Guru Gagal Dihapus.' ] );
    }
}
