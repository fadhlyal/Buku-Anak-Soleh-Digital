<?php

namespace App\Http\Controllers;

use App\Models\ViolationReport;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TeacherViolationReportController extends Controller
{
    public function index_teacher()
    {
        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        return view ( 'teacher.laporan-pelanggaran', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'className' => $className,

            'page' => 'Laporan Pelanggaran Siswa Table'
        ] );
    }

    public function index_student( $studentId )
    {
        $student = Student::find($studentId);
        $studentName = $student->user->name;

        return view ( 'teacher.laporan-pelanggaran-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            
            'page' => 'Laporan Pelanggaran Siswa'
        ] );
    }

    public function index_detail( $reportId )
    {
        $violationReport = ViolationReport::find($reportId);

        $teacherSign = "Belum";

        if($violationReport->teacher_sign) {
            $teacherSign = "Sudah";
        }

        return view ( 'teacher.laporan-pelanggaran-siswa-detail', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'violationReport' => $violationReport,
            'teacherSign' => $teacherSign,

            'page' => 'Detail Laporan Pelanggaran Siswa'
        ] );
    }

    public function index_add_report( $studentId )
    {
        $student = Student::find($studentId);
        $studentName = $student->user->name;

        $today = now()->toDateString();

        return view ( 'teacher.add-laporan-pelanggaran-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'today' => $today,

            'page' => 'Tambah Laporan Pelanggaran Siswa'
        ] );
    }

    public function index_edit_report( $reportId )
    {
        $violationReport = ViolationReport::find($reportId);

        return view ( 'teacher.edit-laporan-pelanggaran-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentName' => $violationReport->student->user->name,
            'violationReport' => $violationReport,
            'reportId' => $reportId,
            'studentId' => $violationReport->student_id,
            'page' => 'Edit Laporan Pelanggaran Siswa'
        ] );
    }

    public function fetchData_laporan_pelanggaran_by_nama_kelas( Request $request )
    {
        // Safely get the length and start, with default values if they're not set
        $length = $request->input ( 'length', 10 ); // Number of records per page
        $start  = $request->input ( 'start', 0 ); // Offset for pagination
        $search = $request->input ( 'search.value', '' ); // Search term, if provided

        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        $query = Student::where('class_name', $className)
            ->with('user', 'violationReports')
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
            $violationReports = $student->violationReports;

            $violationTotal = $violationReports->count();
            $teacherSignFalseCount = $violationReports->where ( 'teacher_sign', false )->count ();

            return [
                'studentNisn'  => $student->nisn,
                'studentName'  => $student->user->name,
                'violationTotal' => $violationTotal,
                'teacherSign'  => $teacherSignFalseCount === 0,
                'action' => view('teacher.partials.laporan-pelanggaran-action-button', ['studentId' => $student->id, 'teacherSign' =>  $teacherSignFalseCount === 0])->render()
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

    public function fetchData_laporan_pelanggaran_by_id_siswa( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided
        $studentId = $request->route('id');

        // Base query to fetch prayer grades for the given student
        $query = ViolationReport::where('student_id', $studentId)
            ->orderByDesc('time_stamp');

        // Apply search filter if available
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('violation_details', 'like', "%{$search}%")
                ->where('consequence', 'like', "%{$search}%");
            });
        }

        // Total records without filtering
        $totalData = ViolationReport::where('student_id', $studentId)->count();

        // Total records after filtering
        $totalFiltered = $query->count();

        // Get paginated results
        $violationReports = $query->offset($start)->limit($length)->get();

        $data = $violationReports->map(function ($violationReport) {

            return [
                'id' => $violationReport->id,
                'timeStamp' => $violationReport->time_stamp->toDateString(),
                'violationDetails' => $violationReport->violation_details,
                'consequence' => $violationReport->consequence,
                'teacherSign'    => $violationReport->teacher_sign,
                'action' => view('teacher.partials.laporan-pelanggaran-siswa-action-button', ['reportId' => $violationReport->id])->render()
            ];
        });

        // Return JSON response for DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function store_violation_report( Request $request )
    {
        $validatedData = $request->validate ( [ 
            'studentId' => 'required|exists:students,id',
            'date' => 'required|date',
            'violation_details' => 'required|string',
            'consequence' => 'required|string',
        ] );

        ViolationReport::create ( [ 
            'student_id' => $validatedData[ 'studentId' ],
            'time_stamp' => $validatedData[ 'date' ],
            'violation_details' => $validatedData[ 'violation_details' ],
            'consequence' => $validatedData[ 'consequence' ],
            'teacher_sign' => false,
        ] );

        return redirect()->route('teacher.laporan-pelanggaran-siswa.index', ['id' => $validatedData['studentId']])
            ->with('success', 'Sukses Menambahkan Data Laporan Baru.');
    }

    public function update_violation_report( Request $request, $reportId )
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'detail_pelanggaran' => 'required|string',
            'konsekuensi' => 'required|string',
        ]);

        $violationReport = ViolationReport::findOrFail($reportId);

        $existingReport = ViolationReport::where('student_id', $validatedData['studentId'])
            ->whereDate('time_stamp', $validatedData['tanggal'])
            ->where('violation_details', $validatedData['detail_pelanggaran'])
            ->where('consequence', $validatedData['konsekuensi'])
            ->first();

        if ($existingReport && $existingReport->id !== $violationReport->id) {
            return redirect ()->back ()->with ( 'error', 'Tidak Dapat Mengganti dengan Data yang Sudah Ada' );
        }

        $violationReport->update([
            'time_stamp' => $validatedData[ 'tanggal' ],
            'violation_details' => $validatedData[ 'detail_pelanggaran' ],
            'consequence' => $validatedData[ 'konsekuensi' ],
            'updated_at' => now(),
        ]);

        return redirect()->route('teacher.laporan-pelanggaran-siswa.index', ['id' => $validatedData['studentId']])
            ->with('success', 'Sukses Mengubah Data Laporan Baru.');
    }

    public function delete_violation_report( Request $request, $reportId )
    {
        $violationReport = ViolationReport::findOrFail ( $reportId );

        $violationReport->delete ();

        return response ()->json ( [ 'success' => 'Data Siswa Berhasil Dihapus.' ] );
    }

    public function teacher_sign_violation_report( Request $request, $reportId )
    {
        $violationReport = ViolationReport::findOrFail ( $reportId );

        $violationReport->teacher_sign = ! $violationReport->teacher_sign;

        $violationReport->save ();

        if ( $violationReport->teacher_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani.' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani.' ] );
    }

    public function violation_report_pdf( Request $request, $studentId )
    {
        $tahun = $request->query('year');
        $bulan = $request->query('month');
        $namaBulan = \Carbon\Carbon::create()->locale('id')->month((int) $bulan)->translatedFormat('F');

        $violationReports = ViolationReport::where('student_id', $studentId)
            ->whereYear('time_stamp', $tahun)
            ->whereMonth('time_stamp', $bulan)
            ->orderBy('time_stamp')
            ->get();

        $student = Student::find($studentId);

        $data = [
            'violationReports' => $violationReports,
            'student' => $student,
            'month' => $namaBulan,
            'year' => $tahun,
        ];

        $pdf = Pdf::loadView('convert.violation-report-template', $data);

        $isLocal = app()->environment('local');
        if( !$isLocal ) {
            $pdf = Pdf::loadView('convert.violation-report-template', $data)
                ->setOptions(['isRemoteEnabled' => true]);
        }

        $fileName = "Lembar Pelanggaran_".$student->class_name."_".$student->user->name."_".$namaBulan."_".$tahun.".pdf";

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function violation_report_approveAll( $studentId )
    {
        $reports = ViolationReport::where('student_id', $studentId)
            ->where('teacher_sign', false)
            ->get();

        foreach ($reports as $report) {
            $report->teacher_sign = true;
            $report->save();
        }

        return redirect()->back()->with('success', 'Semua data sudah ditandatangani');
    }
}
