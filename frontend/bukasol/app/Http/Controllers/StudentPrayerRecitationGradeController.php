<?php

namespace App\Http\Controllers;

use App\Models\PrayerRecitationGrade;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StudentPrayerRecitationGradeController extends Controller
{
    public function index()
    {
        $student = auth ()->user ()->student;
        $studentId = $student->id;

        return view ( 'student.nilai-uji-bacaan-siswa', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,

            'page' => 'Nilai Uji Bacaan Siswa Table'
        ] );
    }

    public function fetchData_nilai_uji_bacaan_by_id_siswa( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided

        $student = auth ()->user ()->student;
        $studentId = $student->id;

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
                'parentSign'     => $prayerRecitationGrade->parent_sign
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

    public function parent_sign_prayer_recitation_grade( Request $request, $gradeId )
    {
        $validated = $request->validate([
            'parentCode' => 'required|string'
        ]);

        $student = auth ()->user ()->student;
        $parentCode = $student->parent_code;

        if ($validated['parentCode'] !== $parentCode) {
            return response()->json([ 'error' => 'Kode Unik Salah.']);
        }

        $prayerRecitationGrade = PrayerRecitationGrade::findOrFail ( $gradeId );
        $prayerRecitationGrade->parent_sign = ! $prayerRecitationGrade->parent_sign;
        $prayerRecitationGrade->save ();

        if ( $prayerRecitationGrade->parent_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani' ] );
    }
}
