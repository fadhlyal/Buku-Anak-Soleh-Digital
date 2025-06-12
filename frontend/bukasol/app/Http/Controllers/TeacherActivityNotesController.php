<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TeacherActivityNotesController extends Controller
{
    public function index_teacher()
    {
        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        return view ( 'teacher.catatan-harian-siswa', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'className' => $className,

            'page' => 'Catatan Aktivitas Harian Siswa Table'
        ] );
    }

    public function index_student( $studentId )
    {
        $student = Student::find($studentId);
        $studentName =  $student->user->name;

        return view ( 'teacher.catatan-harian-siswa-detail', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            
            'page' => 'Catatan Aktivitas Harian Siswa'
        ] );
    }

    public function index_detail( $noteId )
    {
        $activityNote = Note::find($noteId);

        $parentQuestion = $activityNote->parent_question;
        $teacherAnswer = $activityNote->teacher_answer;
        $teacherSign = "Belum";

        if (is_null($activityNote->parent_question)) {
            $parentQuestion = "Tidak Ada Pertanyaan";
        }

        if (is_null($activityNote->teacher_answer)) {
            $teacherAnswer = "Tidak Ada Jawaban dari Pertanyaan Orang Tua";
        }

        if($activityNote->teacher_sign) {
            $teacherSign = "Sudah";
        }

        return view ( 'teacher.catatan-harian-siswa-detail-detail', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'activityNote' => $activityNote,
            'teacherAnswer' => $teacherAnswer,
            'parentQuestion' => $parentQuestion,
            'teacherSign' => $teacherSign,

            'page' => 'Detail Catatan Aktivitas Harian Siswa'
        ] );
    }

    public function index_question( $noteId )
    {
        $activityNote = Note::find($noteId);

        if (is_null($activityNote->parent_question)) {
            return redirect ()->route ( 'teacher.catatan-harian-siswa-detail.index', [ 'id' => $noteId] );
        }

        return view ( 'teacher.answer-catatan-harian-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'activityNote' => $activityNote,
            
            'page' => 'Jawaban Pertanyaan Orang Tua'
        ] );
    }

    public function fetchData_catatan_harian_by_nama_kelas( Request $request )
    {
        // Safely get the length and start, with default values if they're not set
        $length = $request->input ( 'length', 10 ); // Number of records per page
        $start  = $request->input ( 'start', 0 ); // Offset for pagination
        $search = $request->input ( 'search.value', '' ); // Search term, if provided

        $teacher   = auth ()->user ()->teacher;
        $className = $teacher->class_name;

        $query = Student::where('class_name', $className)
            ->with ('user', 'notes')
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
            $activityNotes = $student->notes;

            $teacherSignFalseCount = $activityNotes->where ( 'teacher_sign', false )->count ();
            $parentQuestion = $activityNotes->whereNotNull('parent_question')
                                            ->whereNull('teacher_answer')
                                            ->count();

            return [ 
                'studentNisn'  => $student->nisn,
                'studentName'  => $student->user->name,
                'parentQuestion' => $parentQuestion === 0,
                'teacherSign'  => $teacherSignFalseCount === 0,
                'action' => view('teacher.partials.catatan-harian-siswa-action-button', ['studentId' => $student->id, 'teacherSign'  => $teacherSignFalseCount === 0 ])->render()
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

    public function fetchData_catatan_harian_by_id_siswa( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided
        $studentId = $request->route('id');

        // Base query to fetch prayer grades for the given student
        $query = Note::where('student_id', $studentId)
            ->orderByDesc('time_stamp');

         // Apply search filter if available
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('agenda', 'like', "%{$search}%")
                ->where('content', 'like', "%{$search}%");
            });
        }

        // Total records without filtering
        $totalData = Note::where('student_id', $studentId)->count();

        // Total records after filtering
        $totalFiltered = $query->count();

        // Get paginated results
        $activityNotes = $query->offset($start)->limit($length)->get();

        $data = $activityNotes->map(function ($activityNote) {
            $parentQuestion = "Tidak Ada Pertanyaan";
            $parentQuestionClass = "";

            if( !is_null($activityNote->parent_question) && !is_null($activityNote->teacher_answer)) {
                $parentQuestion = "Pertanyaan Sudah Dijawab";
                $parentQuestionClass = "text-success";
            }

            if( !is_null($activityNote->parent_question) && is_null($activityNote->teacher_answer)) {
                $parentQuestion = "Pertanyaan Belum Dijawab";
                $parentQuestionClass = "text-danger";
            }

            return [
                'id' => $activityNote->id,
                'timeStamp' => $activityNote->time_stamp->toDateString(),
                'agenda' => $activityNote->agenda,
                'content' => $activityNote->content,
                'parentQuestion' => "<span class='{$parentQuestionClass}'>{$parentQuestion}</span>",
                'teacherSign'    => $activityNote->teacher_sign,
                'action' => view('teacher.partials.catatan-harian-siswa-detail-action-button', ['noteId' => $activityNote->id])->render()
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

    public function answer_parent_question( Request $request, $noteId )
    {
        $validatedData = $request->validate ( [
            'jawaban_guru' => 'required|string'
        ] );

        $noteActivity = Note::findOrFail($noteId);

        $noteActivity->update([
            'teacher_answer' => $validatedData['jawaban_guru'],
            'time_stamp' => now(),
        ]);

        return redirect()->route('teacher.catatan-harian-siswa-detail.index', ['id' => $noteId])
            ->with('success', 'Menjawab Pertanyaan Orang Tua.');
    }

    public function teacher_sign_activity_notes( Request $request, $noteId )
    {
        $activityNote = Note::findOrFail ( $noteId );

        $activityNote->teacher_sign = ! $activityNote->teacher_sign;

        $activityNote->save ();

        if ( $activityNote->teacher_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani.' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani.' ] );
    }

    public function activity_notes_pdf( Request $request, $studentId )
    {
        $tahun = $request->query('year');
        $bulan = $request->query('month');
        $namaBulan = \Carbon\Carbon::create()->month( (int) $bulan)->translatedFormat('F');

        $noteActivities = Note::where('student_id', $studentId)
                            ->whereYear('time_stamp', $tahun)
                            ->whereMonth('time_stamp', $bulan)
                            ->orderBy('time_stamp')
                            ->get();

        $student = Student::find($studentId);

        $data = [
            'noteActivities' => $noteActivities,
            'student' => $student,
        ];

        $pdf = Pdf::loadView('convert.activity-note-template', $data);
        $fileName = "Lembar Catatan Harian_".$student->class_name."_".$student->user->name."_".$namaBulan."_".$tahun.".pdf";

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function activity_notes_approveAll( $studentId )
    {
        $reports = Note::where('student_id', $studentId)
            ->where('teacher_sign', false)
            ->get();

        foreach ($reports as $report) {
            $report->teacher_sign = true;
            $report->save();
        }

        return redirect()->back()->with('success', 'Semua data sudah ditandatangani');
    }
}
