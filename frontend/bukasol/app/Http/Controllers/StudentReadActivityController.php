<?php

namespace App\Http\Controllers;

use App\Models\ReadActivity;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StudentReadActivityController extends Controller
{
    public function index_table()
    {
        $student = auth ()->user ()->student;
        $studentId = $student->id;

        return view ( 'student.aktivitas-membaca-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,

            'page' => 'Aktivitas Membaca Siswa Table'
        ] );
    }

    public function index_add_activity()
    {
        $student = auth ()->user ()->student;
        $studentId = $student->id;
        $studentName = $student->user->name;

        $today = now()->toDateString();

        return view ( 'student.add-aktivitas-membaca-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'today' => $today,

            'page' => 'Tambah Aktivitas Membaca Siswa'
        ] );
    }

    public function index_edit_activity( $noteId )
    {
        $readActivity = ReadActivity::find($noteId);

        return view ( 'student.edit-aktivitas-membaca-siswa', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentName' => $readActivity->student->user->name,
            'readActivity' => $readActivity,
            'noteId' => $noteId,
            'studentId' => $readActivity->student_id,
            'page' => 'Edit Aktivitas Membaca Siswa'
        ] );
    }

    public function fetchData_aktivitas_membaca_by_id_siswa( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided

        $student = auth ()->user ()->student;
        $studentId = $student->id;

        // Base query to fetch prayer grades for the given student
        $query = ReadActivity::where('student_id', $studentId)->orderByDesc('time_stamp');

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
                'parentSign' => $readActivity->parent_sign,
                'action' => view('student.partials.aktivitas-membaca-siswa-action-button', ['noteId' => $readActivity->id])->render()
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

    public function store_reading_activity( Request $request )
    {
        $validatedData = $request->validate ( [ 
            'studentId'      => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'judul_buku' => 'required|string|max:255',
            'halaman_awal' => 'required|string|max:255',
            'halaman_akhir' => 'required|string|max:255',
        ] );
        
        $existingActivity = ReadActivity::where('student_id', $validatedData['studentId'])
            ->whereDate('time_stamp', $validatedData['tanggal'])
            ->where('book_title', $validatedData['judul_buku'])
            ->where('page', $validatedData['halaman_awal']."-".$validatedData['halaman_akhir'])
            ->first();

        if ($existingActivity) {
            return redirect ()->back ()->with ( 'error', 'Data dengan Tanggal, Judul, dan Halaman Tersebut sudah Dibuat.' );
        }

        ReadActivity::create ( [
            'student_id' => $validatedData[ 'studentId' ],
            'time_stamp' => $validatedData[ 'tanggal' ],
            'book_title'  => $validatedData[ 'judul_buku' ],
            'page'  => $validatedData[ 'halaman_awal' ]."-".$validatedData[ 'halaman_akhir' ],
            'teacher_sign' => false,
            'parent_sign' => false,
        ] );

        return redirect()->route('student.aktivitas-membaca-siswa-table.index')
            ->with('success', 'Sukses Menambahkan Data Aktivitas Baru.');
    }

    public function update_reading_activity( Request $request, $noteId )
    {
        $validatedData = $request->validate ( [ 
            'studentId'      => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'judul_buku' => 'required|string|max:255',
            'halaman_awal' => 'required|string|max:255',
            'halaman_akhir' => 'required|string|max:255',
        ] );

        $readActivity = ReadActivity::findOrFail($noteId);

        $existingActivity = ReadActivity::where('student_id', $validatedData['studentId'])
            ->whereDate('time_stamp', $validatedData['tanggal'])
            ->where('book_title', $validatedData['judul_buku'])
            ->where('page', $validatedData['halaman_awal']."-".$validatedData['halaman_akhir'])
            ->first();

        if ($existingActivity && $existingActivity->id !== $readActivity->id) {
            return redirect ()->back ()->with ( 'error', 'Tidak Dapat Mengganti dengan Data yang Sudah Ada' );
        }

        $readActivity->update([
            'time_stamp' => $validatedData['tanggal'],
            'book_title' => $validatedData['judul_buku'],
            'page' => $validatedData['halaman_awal']."-".$validatedData['halaman_akhir'],
            'updated_at' => now(),
        ]);

        return redirect()->route('student.aktivitas-membaca-siswa-table.index')
            ->with('success', 'Sukses Mengubah Data Aktivitas Baru.');
    }

    public function delete_reading_activity( Request $request, $noteId )
    {
        $readActivity = ReadActivity::findOrFail ( $noteId );

        $readActivity->delete ();

        return response ()->json ( [ 'success' => 'Data Siswa Berhasil Dihapus.' ] );
    }

    public function parent_sign_reading_activity( Request $request, $noteId )
    {
        $validated = $request->validate([
            'parentCode' => 'required|string'
        ]);

        $student = auth ()->user ()->student;
        $parentCode = $student->parent_code;

        if ($validated['parentCode'] !== $parentCode) {
            return response()->json([ 'error' => 'Kode Unik Salah.']);
        }

        $readActivity = ReadActivity::findOrFail ( $noteId );
        $readActivity->parent_sign = ! $readActivity->parent_sign;
        $readActivity->save ();

        if ( $readActivity->parent_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani.' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani.' ] );
    }
}
