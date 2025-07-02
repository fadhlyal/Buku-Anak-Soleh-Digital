<?php

namespace App\Http\Controllers;

use App\Models\PrayerGrade;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StudentPrayerGradeController extends Controller
{

    public function index()
    {
        $student = auth ()->user ()->student;
        $studentId = $student->id;

        return view ( 'student.nilai-uji-gerakan-siswa', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,

            'page' => 'Nilai Uji Gerakan Siswa Table'
        ] );
    }

    public function fetchData_nilai_uji_gerakan_by_id_siswa( Request $request )
    {
        
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided

        $student = auth ()->user ()->student;
        $studentId = $student->id;

        // Base query to fetch prayer grades for the given student
        $query = PrayerGrade::where('student_id', $studentId)
            ->orderByDesc('time_stamp')
            ->orderBy('motion_category');

         // Apply search filter if available
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('motion_category', 'like', "%{$search}%");
            });
        }

        // Total records without filtering
        $totalData = PrayerGrade::where('student_id', $studentId)->count();

        // Total records after filtering
        $totalFiltered = $query->count();

        // Get paginated results
        $prayerGrades = $query->offset($start)->limit($length)->get();

        $data = $prayerGrades->map(function ($prayerGrade) {
            return [
                'id' => $prayerGrade->id,
                'timeStamp'      => $prayerGrade->time_stamp->toDateString(),
                'motionCategory' => $prayerGrade->motion_category,
                'gradeSemester1' => number_format($prayerGrade->grade_semester1, 2),
                'gradeSemester2' => number_format($prayerGrade->grade_semester2, 2),
                'teacherSign'    => $prayerGrade->teacher_sign,
                'parentSign'     => $prayerGrade->parent_sign
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

    public function parent_sign_prayer_grade( Request $request, $gradeId )
    {
        $validated = $request->validate([
            'parentCode' => 'required|string'
        ]);

        $student = auth ()->user ()->student;
        $parentCode = $student->parent_code;

        if ($validated['parentCode'] !== $parentCode) {
            return response()->json([ 'error' => 'Kode Unik Salah']);
        }

        $prayerGrade = PrayerGrade::findOrFail ( $gradeId );
        $prayerGrade->parent_sign = ! $prayerGrade->parent_sign;
        $prayerGrade->save ();

        if ( $prayerGrade->parent_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani' ] );
    }
}