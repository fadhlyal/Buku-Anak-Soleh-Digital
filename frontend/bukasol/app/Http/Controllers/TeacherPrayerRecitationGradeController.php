<?php

namespace App\Http\Controllers;

use App\Models\PrayerRecitationGrade;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TeacherPrayerRecitationGradeController extends Controller
{
    public function index_teacher ()
    {
        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        return view ( 'teacher.nilai-uji-bacaan-siswa', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'className' => $className,

            'page' => 'Nilai Uji Bacaan Siswa Table'
        ] );
    }

    public function index_student ( $studentId )
    {
        $student = Student::find($studentId);
        $studentName = $student->user->name;

        return view ( 'teacher.nilai-uji-bacaan-siswa-detail', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'page' => 'Nilai Uji Bacaan Siswa'
        ] );
    }

    public function index_add_grade ( $studentId )
    {
        $student = Student::find($studentId);
        $studentName = $student->user->name;

        return view ( 'teacher.add-nilai-uji-bacaan-siswa', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'page' => 'Tambah Nilai Uji Bacaan Siswa'
        ] );
    }

    public function index_edit_grade ( $gradeId )
    {
        $prayerRecitationGrade = PrayerRecitationGrade::find($gradeId);

        return view ( 'teacher.edit-nilai-uji-bacaan-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentName' => $prayerRecitationGrade->student->user->name,
            'gradeId' => $gradeId,
            'readingCategory' => $prayerRecitationGrade->reading_category,
            'gradeSemester1' => $prayerRecitationGrade->grade_semester1,
            'gradeSemester2' => $prayerRecitationGrade->grade_semester2,
            'studentId' => $prayerRecitationGrade->student_id,
            'page' => 'Edit Nilai Uji Bacaan Siswa'
        ] );
    }

    public function fetchData_nilai_uji_bacaan_by_nama_kelas ( Request $request )
    {
        // Safely get the length and start, with default values if they're not set
        $length = $request->input ( 'length', 10 ); // Number of records per page
        $start  = $request->input ( 'start', 0 ); // Offset for pagination
        $search = $request->input ( 'search.value', '' ); // Search term, if provided

        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        $query = Student::where('class_name', $className)
            ->with('user', 'prayerRecitationGrades')
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
            $prayerRecitationGrades = $student->prayerRecitationGrades;

            $avgSemester1 = $prayerRecitationGrades->avg ( 'grade_semester1' ) ?? 0.0;
            $avgSemester2 = $prayerRecitationGrades->avg ( 'grade_semester2' ) ?? 0.0;

            $parentSignFalseCount  = $prayerRecitationGrades->where ( 'parent_sign', false )->count ();
            $teacherSignFalseCount = $prayerRecitationGrades->where ( 'teacher_sign', false )->count ();

            return [ 
                'studentNisn'  => $student->nisn,
                'studentName'  => $student->user->name,
                'avgSemester1' => number_format ( $avgSemester1, 2 ),
                'avgSemester2' => number_format ( $avgSemester2, 2 ),
                'parentSign'   => $parentSignFalseCount === 0,
                'teacherSign'  => $teacherSignFalseCount === 0,
                'action' => view('teacher.partials.nilai-uji-bacaan-siswa-action-button', ['studentId' => $student->id, 'teacherSign'  => $teacherSignFalseCount === 0 ])->render() 
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

    public function fetchData_nilai_uji_bacaan_by_id_siswa ( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided
        $studentId = $request->route('id');

        // Base query to fetch prayer grades for the given student
        $query = PrayerRecitationGrade::where('student_id', $studentId)
            ->orderByDesc('time_stamp')
            ->orderBy('reading_category');

         // Apply search filter if available
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('reading_category', 'like', "%{$search}%");
            });
        }

        // Total records without filtering
        $totalData = PrayerRecitationGrade::where('student_id', $studentId)->count();

        // Total records after filtering
        $totalFiltered = $query->count();

        // Get paginated results
        $prayerRecitationGrades = $query->offset($start)->limit($length)->get();

        $data = $prayerRecitationGrades->map(function ($prayerRecitationGrade) {
            return [
                'id' => $prayerRecitationGrade->id,
                'timeStamp'      => $prayerRecitationGrade->time_stamp->toDateString(),
                'readingCategory' => $prayerRecitationGrade->reading_category,
                'gradeSemester1' => number_format($prayerRecitationGrade->grade_semester1, 2),
                'gradeSemester2' => number_format($prayerRecitationGrade->grade_semester2, 2),
                'teacherSign'    => $prayerRecitationGrade->teacher_sign,
                'parentSign'     => $prayerRecitationGrade->parent_sign,
                'action' => view('teacher.partials.nilai-uji-bacaan-siswa-detail-action-button', ['gradeId' => $prayerRecitationGrade->id])->render() 
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

    public function store_prayer_recitation_grade( Request $request )
    {
        $validatedData = $request->validate ( [ 
            'studentId'      => 'required|exists:students,id',
            'jenis_bacaan' => 'required|string|max:255',
            'nilai_semester_1' => 'required|numeric|min:0|max:100',
            'nilai_semester_2' => 'required|numeric|min:0|max:100',
        ] );

        $existingGrade = PrayerRecitationGrade::where('student_id', $validatedData['studentId'])
            ->where('reading_category', $validatedData['jenis_bacaan'])
            ->first();

        if ($existingGrade) {
            return redirect ()->back ()->with ( 'error', 'Data Nilai dengan Bacaan Tersebut sudah ada.' );
        }

        PrayerRecitationGrade::create ( [ 
            'student_id'       => $validatedData[ 'studentId' ],
            'time_stamp'       => now (),
            'reading_category'  => $validatedData[ 'jenis_bacaan' ],
            'grade_semester1'  => $validatedData[ 'nilai_semester_1' ],
            'grade_semester2'  => $validatedData[ 'nilai_semester_2' ],
            'teacher_sign'     => false,
            'teacher_sign'     => false,
        ] );

        return redirect()->route('teacher.nilai-uji-bacaan-siswa-detail.index', ['id' => $validatedData['studentId']])
            ->with('success', 'Sukses Menambahkan Data Nilai Baru.');
    }

    public function update_prayer_recitation_grade( Request $request, $gradeId )
    {
        $validatedData = $request->validate ( [
            'studentId'      => 'required|exists:students,id',
            'jenis_bacaan' => 'required|string|max:255',
            'nilai_semester_1' => 'required|numeric|min:0|max:100',
            'nilai_semester_2' => 'required|numeric|min:0|max:100',
        ] );

        $prayerRecitationGrade = PrayerRecitationGrade::findOrFail($gradeId);

        $existingGrade = PrayerRecitationGrade::where('student_id', $validatedData['studentId'])
            ->where('reading_category', $validatedData['jenis_bacaan'])
            ->where('id', '!=', $gradeId)
            ->first();

            if ($existingGrade) {
                return redirect ()->back ()->with ( 'error', 'Tidak Dapat Mengganti dengan Data yang Sudah Ada' );
            }

        $prayerRecitationGrade->update([
            'reading_category'  => $validatedData['jenis_bacaan'],
            'grade_semester1'  => $validatedData['nilai_semester_1'],
            'grade_semester2'  => $validatedData['nilai_semester_2'],
            'time_stamp'       => now(),
        ]);

        return redirect()->route('teacher.nilai-uji-bacaan-siswa-detail.index', ['id' => $validatedData['studentId']])
            ->with('success', 'Sukses Mengubah Data Nilai Baru.');
    }

    public function delete_prayer_recitation_grade( Request $request, $gradeId )
    {
       
        $prayerRecitationGrade = PrayerRecitationGrade::findOrFail ( $gradeId );

        $prayerRecitationGrade->delete ();

        return response ()->json ( [ 'success' => 'Data Siswa Berhasil Dihapus.' ] );
    }

    public function teacher_sign_prayer_recitation_grade( Request $request, $gradeId )
    {
        
        $prayerRecitationGrade = PrayerRecitationGrade::findOrFail ( $gradeId );

        $prayerRecitationGrade->teacher_sign = ! $prayerRecitationGrade->teacher_sign;

        $prayerRecitationGrade->save ();

        if ( $prayerRecitationGrade->teacher_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani.' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani.' ] );
    }

    public function prayer_recitation_grade_pdf( $studentId )
    {

        $prayerRecitationGrades = PrayerRecitationGrade::where('student_id', $studentId)
            ->orderBy('time_stamp')
            ->orderBy('reading_category')
            ->get();

        $student = Student::find($studentId);

        $teacherSign = PrayerRecitationGrade::where('student_id', $studentId)
            ->where('teacher_sign', false)
            ->count() === 0 ? 'Sudah' : 'Belum';

        $parentSign = PrayerRecitationGrade::where('student_id', $studentId)
            ->where('parent_sign', false)
            ->count() === 0 ? 'Sudah' : 'Belum';

        $data = [
            'prayerRecitationGrades' => $prayerRecitationGrades,
            'student' => $student,
            'teacherSign' => $teacherSign,
            'parentSign' => $parentSign,
            'dateToday' => now(),
        ];

        $pdf = Pdf::loadView('convert.prayer-recitation-grade-template', $data);
        $fileName = "Lembar Nilai Uji Bacaan".$student->class_name."_".$student->user->name.".pdf";

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function prayer_recitation_grade_approveAll( $studentId )
    {
        $reports = PrayerRecitationGrade::where('student_id', $studentId)
            ->where('teacher_sign', false)
            ->get();

        foreach ($reports as $report) {
            $report->teacher_sign = true;
            $report->save();
        }

        return redirect()->back()->with('success', 'Semua data sudah ditandatangani');
    }
}
