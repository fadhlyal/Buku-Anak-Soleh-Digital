<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminDashboardController extends Controller
{
    public function index ()
    {
        return redirect ()->route ( 'admin.student-table.index' );
    }

    public function student_table_index ()
    {
        $auth = auth ()->user ();

        return view ( 'admin.student-table', [ 
            'role' => $auth->role,
            'name' => $auth->name,

            'page' => "Student Table"
        ] );
    }

    public function teacher_table_index ()
    {
        $auth = auth ()->user ();

        return view ( 'admin.teacher-table', [ 
            'role' => $auth->role,
            'name' => $auth->name,

            'page' => "Teacher Table"
        ] );
    }

    public function student_add_index ()
    {
        $auth     = auth ()->user ();
        $teachers = Teacher::with ( 'user' )->get ()->all ();

        return view ( 'admin.add-student', [ 
            'role'     => $auth->role,
            'name'     => $auth->name,
            'teachers' => $teachers,

            'page'     => "Add Student"
        ] );
    }

    public function teacher_add_index ()
    {
        $auth = auth ()->user ();

        return view ( 'admin.add-teacher', [ 
            'role' => $auth->role,
            'name' => $auth->name,

            'page' => "Add Teacher"
        ] );
    }

    public function student_detail_index ( $id )
    {
        $auth    = auth ()->user ();
        $student = Student::with ( 'user', 'teacher.user' )->find ( $id );

        $letters = strtolower(explode(' ', $student->user->name)[0]);
        $numbers = substr($student->nisn, -4);
        $password = $letters . $numbers;

        return view ( 'admin.student-detail', [ 
            'role'    => $auth->role,
            'name'    => $auth->name,
            'student' => $student,
            'password' => $password,

            'page'    => "Detail Student"
        ] );
    }

    public function teacher_detail_index ( $id )
    {
        $auth    = auth ()->user ();
        $teacher = Teacher::with ( 'user' )->find ( $id );

        $letters = strtolower(explode(' ', $teacher->user->name)[0]);
        $numbers = substr($teacher->nip, -4);
        $password = $letters . $numbers;

        return view ( 'admin.teacher-detail', [ 
            'role'    => $auth->role,
            'name'    => $auth->name,
            'teacher' => $teacher,
            'password' => $password,

            'page'    => "Detail Teacher"
        ] );
    }

    public function fetchData_student ()
    {
        // Safely get the length and start, with default values if they're not set
        $length = request ( 'length' ) ?? 10; // Number of records per page
        $start  = request ( 'start' ) ?? 0; // Offset for pagination
        $search = request ( 'search' )[ 'value' ] ?? ''; // Search term, if provided

        $query = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*', 'users.name as user_name');

        // Apply search filter if available
        if ( ! empty ( $search ) )
        {
            $query->where ( 'nisn', 'like', "%{$search}%" )
                ->orWhere ( 'class_name', 'like', "%{$search}%" )
                ->orWhereHas ( 'user', function ($q) use ($search)
                {
                    $q->where ( 'name', 'like', "%{$search}%" );
                } );
        }
        $query->orderBy('students.class_name')->orderBy('users.name');

        // Total records without filtering
        $totalData = Student::count ();

        // Total records after filtering
        $totalFiltered = $query->count ();

        // Get paginated results
        $students = $query->offset ( $start )->limit ( $length )->get ();

        // Map data for DataTables response
        $data = $students->map ( function ($student)
        {
            $letters = strtolower(explode(' ', $student->user->name)[0]);
            $numbers = substr($student->nisn, -4);
            $password = $letters . $numbers;

            return [
                'nisn' => $student->nisn,
                'name' => $student->user->name ?? 'N/A',
                'className' => $student->class_name,
                'username' => $student->user->username,
                'password' => $password,
                'parentCode' => $student->parent_code,
                'action' => view ( 'admin.partials.student-table-actions', [ 'student' => $student ] )->render (),
            ];
        } );

        // Return JSON response
        return response ()->json ( [
            'draw'            => intval ( request ( 'draw' ) ), // Draw counter for DataTables
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ] );
    }

    public function fetchData_teacher ()
    {
        // Safely get the length and start, with default values if they're not set
        $length = request ( 'length' ) ?? 10; // Number of records per page
        $start  = request ( 'start' ) ?? 0; // Offset for pagination
        $search = request ( 'search' )[ 'value' ] ?? ''; // Search term, if provided

        $query = Teacher::query()
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*', 'users.name as user_name');

        // Apply search filter if available
        if ( ! empty ( $search ) )
        {
            $query->where ( 'nip', 'like', "%{$search}%" )
                ->orWhere ( 'class_name', 'like', "%{$search}%" )
                ->orWhereHas ( 'user', function ($q) use ($search)
                {
                    $q->where ( 'name', 'like', "%{$search}%" );
                } );
        }
        $query->orderBy('teachers.class_name')->orderBy('users.name');

        // Total records without filtering
        $totalData = Teacher::count ();

        // Total records after filtering
        $totalFiltered = $query->count ();

        // Get paginated results
        $teachers = $query->offset ( $start )->limit ( $length )->get ();

        // Map data for DataTables response
        $data = $teachers->map ( function ($teacher)
        {
            $letters = strtolower(explode(' ', $teacher->user->name)[0]);
            $numbers = substr($teacher->nip, -4);
            $password = $letters . $numbers;

            return [
                'nip' => $teacher->nip,
                'name' => $teacher->user->name ?? 'N/A',
                'className' => $teacher->class_name,
                'username' => $teacher->user->username,
                'password' => $password,
                'action' => view ( 'admin.partials.teacher-table-actions', [ 'teacher' => $teacher ] )->render (),
            ];
        } );

        // Return JSON response
        return response ()->json ( [
            'draw'            => intval ( request ( 'draw' ) ), // Draw counter for DataTables
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ] );
    }

    public function student_account_pdf()
    {
        $students = Student::orderBy('class_name')->get();

        $students->each(function ($student) {
            $letters = strtolower(explode(' ', $student->user->name)[0]);
            $numbers = substr($student->nisn, -4);
            $student->new_password = $letters . $numbers;
        });

        $data = [
            'students' => $students,
        ];

        $pdf = Pdf::loadView('convert.student-account-template', $data);
        $fileName = "Akun Siswa.pdf";

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function teacher_account_pdf()
    {
        $teachers = Teacher::orderBy('class_name')->get();

        $teachers->each(function ($teacher) {
            $letters = strtolower(explode(' ', $teacher->user->name)[0]);
            $numbers = substr($teacher->nip, -4);
            $teacher->new_password = $letters . $numbers;
        });

        $data = [
            'teachers' => $teachers,
        ];

        $pdf = Pdf::loadView('convert.teacher-account-template', $data);
        $fileName = "Akun Guru.pdf";

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function student_import_account( Request $request )
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');

        return response()->json(['success' => $file->getClientOriginalName()]);
    }

    public function teacher_import_account( Request $request )
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');

        return response()->json(['success' => $file->getClientOriginalName()]);
    }
}
