<?php

namespace App\Http\Controllers;

use App\Models\ReadActivity;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TeacherReadActivityController extends Controller
{
    public function index_teacher()
    {
        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        return view ( 'teacher.aktivitas-membaca-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'className' => $className,

            'page' => 'Aktivitas Membaca Siswa Table'
        ] );
    }

    public function index_student( $studentId )
    {
        $student = Student::find($studentId);
        $studentName = $student->user->name;

        return view ( 'teacher.aktivitas-membaca-siswa-detail', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            
            'page' => 'Aktivitas Membaca Siswa'
        ] );
    }

    public function fetchData_aktivitas_membaca_by_nama_kelas( Request $request )
    {
        // Safely get the length and start, with default values if they're not set
        $length = $request->input ( 'length', 10 ); // Number of records per page
        $start  = $request->input ( 'start', 0 ); // Offset for pagination
        $search = $request->input ( 'search.value', '' ); // Search term, if provided

        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        $query = Student::where('class_name', $className)
            ->with('user', 'readActivities')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*', 'users.name as user_name')
            ->orderBy('user_name');

        // Apply search filter if available
        if ( ! empty ( $search ) )
        {
            $query->where ( function ($q) use ($search)
            {
                $q->where ( 'nisn', 'like', "%{$search}%" )
                    ->orWhereHas ( 'user', function ($subQuery) use ($search)
                    {
                        $subQuery->where ( 'name', 'like', "%{$search}%" );
                    } );
            } );
        }

        // Total records without filtering
        $totalData = Student::where ( 'class_name', $className )->count ();

        // Total records after filtering
        $totalFiltered = $query->count ();

        // Get paginated results
        $students = $query->offset ( $start )->limit ( $length )->get ();

        // Map data for DataTables response
        $data = $students->map ( function ($student)
        {
            $readActivities = $student->readActivities;

            $totalReadActivities = $readActivities->count();

            $parentSignFalseCount  = $readActivities->where ( 'parent_sign', false )->count ();
            $teacherSignFalseCount = $readActivities->where ( 'teacher_sign', false )->count ();

            return [
                'studentNisn'  => $student->nisn,
                'studentName'  => $student->user->name,
                'totalActivity' => $totalReadActivities,
                'teacherSign'  => $teacherSignFalseCount === 0,
                'parentSign'  => $parentSignFalseCount === 0,
                'action' => view('teacher.partials.aktivitas-membaca-siswa-action-button', ['studentId' => $student->id, 'teacherSign'  => $teacherSignFalseCount === 0])->render()
            ];
        } );

        // Return JSON response
        return response ()->json ( [
            'draw'            => intval ( $request->input ( 'draw' ) ),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ] );
    }

    public function fetchData_aktivitas_membaca_by_id_siswa( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided
        $studentId = $request->route('id');

        // Base query to fetch prayer grades for the given student
        $query = ReadActivity::where('student_id', $studentId)
            ->orderByDesc('time_stamp');

        // Apply search filter if available
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('book_title', 'like', "%{$search}%");
            });
        }

        // Total records without filtering
        $totalData = ReadActivity::where('student_id', $studentId)->count();

        // Total records after filtering
        $totalFiltered = $query->count();

        // Get paginated results
        $readActivities = $query->offset($start)->limit($length)->get();

        $data = $readActivities->map(function ($readActivity) {
            return [
                'id' => $readActivity->id,
                'timeStamp' => $readActivity->time_stamp->toDateString(),
                'bookTitle' => $readActivity->book_title,
                'page' => $readActivity->page,
                'teacherSign' => $readActivity->teacher_sign,
                'parentSign' => $readActivity->parent_sign
            ];
        });

        // Return JSON response
        return response ()->json ( [
            'draw'            => intval ( $request->input ( 'draw' ) ),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ] );
    }

    public function teacher_sign_read_activity( Request $request, $noteId )
    {
        $readActivity = ReadActivity::findOrFail ( $noteId );

        $readActivity->teacher_sign = ! $readActivity->teacher_sign;

        $readActivity->save ();

        if ( $readActivity->teacher_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani.' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani.' ] );
    }

    public function reading_activity_pdf( Request $request, $studentId )
    {
        $tahun = $request->query('year');
        $bulan = $request->query('month');
        $namaBulan = \Carbon\Carbon::create()->month( (int) $bulan)->translatedFormat('F');

        $readActivities = ReadActivity::where('student_id', $studentId)
                                ->whereYear('time_stamp', $tahun)
                                ->whereMonth('time_stamp', $bulan)
                                ->orderBy('time_stamp')
                                ->get();
                                
        $student = Student::find($studentId);

        $data = [
            'readActivities' => $readActivities,
            'student' => $student,
        ];

        $pdf = Pdf::loadView('convert.reading-activity-template', $data)
                ->setOptions(['isRemoteEnabled' => true]);
        $fileName = "Lembar Aktivitas Membaca_".$student->class_name."_".$student->user->name."_".$namaBulan."_".$tahun.".pdf";

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function read_activity_approveAll( $studentId )
    {
        $reports = ReadActivity::where('student_id', $studentId)
            ->where('teacher_sign', false)
            ->get();

        foreach ($reports as $report) {
            $report->teacher_sign = true;
            $report->save();
        }

        return redirect()->back()->with('success', 'Semua data sudah ditandatangani');
    }
}
