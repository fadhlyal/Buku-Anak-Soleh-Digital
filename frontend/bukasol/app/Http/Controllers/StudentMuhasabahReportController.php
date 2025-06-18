<?php

namespace App\Http\Controllers;

use App\Models\MuhasabahReport;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StudentMuhasabahReportController extends Controller
{
    public function index_table()
    {
        $student = auth ()->user ()->student;
        $studentId = $student->id;

        return view ( 'student.laporan-muhasabah-harian', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,

            'page' => 'Laporan Muhasabah Siswa Table'
        ] );
    }

    public function index_detail( $reportId )
    {
        $muhasabahReport = MuhasabahReport::find($reportId);

        $surahName = "-";
        $surahAyat = "-";

        if ( !is_null($muhasabahReport->surah_name)) {
            $surahName = $muhasabahReport->surah_name;
        }

        if ( !is_null($muhasabahReport->surah_ayat)) {
            $surahAyat = $muhasabahReport->surah_ayat;
        }

        return view ( 'student.laporan-muhasabah-harian-detail', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'muhasabahReport' => $muhasabahReport,
            'surahName' => $surahName,
            'surahAyat' => $surahAyat,

            'page' => 'Detail Laporan Muhasabah Siswa'
        ] );
    }

    public function index_add_report()
    {
        $student = auth ()->user ()->student;
        $studentId = $student->id;
        $studentName = $student->user->name;

        $today = now()->toDateString();

        return view ( 'student.add-laporan-muhasabah-harian', [ 
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'today' => $today,

            'page' => 'Tambah Laporan Muhasabah Siswa'
        ] );
    }

    public function index_edit_report( $reportId )
    {
        $muhasabahReport = MuhasabahReport::find($reportId);

        return view ( 'student.edit-laporan-muhasabah-harian', [
            'role' => auth ()->user ()->role,
            'name' => auth ()->user ()->name,
            'studentName' => $muhasabahReport->student->user->name,
            'muhasabahReport' => $muhasabahReport,
            'reportId' => $reportId,
            'studentId' => $muhasabahReport->student_id,
            'page' => 'Edit Laporan Muhasabah Siswa'
        ] );
    }

    public function fetchData_laporan_muhasabah_by_id_siswa( Request $request )
    {
        // Safely get the length and start for pagination
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset for pagination
        $search = $request->input('search.value', ''); // Search term, if provided
         
        $student = auth ()->user ()->student;
        $studentId = $student->id;
 
        // Base query to fetch prayer grades for the given student
        $query = MuhasabahReport::where('student_id', $studentId)->orderByDesc('time_stamp');

        // Apply search filter if available
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
            });
        }

        // Total records without filtering
        $totalData = MuhasabahReport::where('student_id', $studentId)->count();

        // Total records after filtering
        $totalFiltered = $query->count();

        // Get paginated results
        $muhasabahReports = $query->offset($start)->limit($length)->get();

        $data = $muhasabahReports->map(function ($muhasabahReport) {
            $mengaji = false;

            if ( !is_null($muhasabahReport->surah_name) && !is_null($muhasabahReport->surah_ayat)) {
                $mengaji = true;
            }

            $countFardhuPray = 0;

            if ($muhasabahReport->subuh_pray) {
                $countFardhuPray++;
            }

            if ($muhasabahReport->dzuhur_pray) {
                $countFardhuPray++;
            }

            if ($muhasabahReport->ashar_pray) {
                $countFardhuPray++;
            }

            if ($muhasabahReport->maghrib_pray) {
                $countFardhuPray++;
            }

            if ($muhasabahReport->isya_pray) {
                $countFardhuPray++;
            }

            $fardhuPray = $countFardhuPray . "/5";

            return [
                'id' => $muhasabahReport->id,
                'timeStamp' => $muhasabahReport->time_stamp->toDateString(),
                'mengaji' => $mengaji,
                'shalatSunnah' => $muhasabahReport->sunnah_pray,
                'shalatFardhu' => $fardhuPray,
                'teacherSign' => $muhasabahReport->teacher_sign,
                'parentSign' => $muhasabahReport->parent_sign,
                'action' => view('student.partials.laporan-muhasabah-harian-action-button', ['reportId' => $muhasabahReport->id])->render()
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

    public function store_muhasabah_report( Request $request )
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'surah' => 'nullable|string|max:255',
            'ayat_awal' => 'nullable|string|max:255',
            'ayat_akhir' => 'nullable|string|max:255',
            'shalat_sunnah' => 'required|string|in:Sudah,Tidak',
            'subuh' => 'required|string|in:Sudah,Tidak',
            'dzuhur' => 'required|string|in:Sudah,Tidak',
            'ashar' => 'required|string|in:Sudah,Tidak',
            'maghrib' => 'required|string|in:Sudah,Tidak',
            'isya' => 'required|string|in:Sudah,Tidak',
        ]);

        $existingReport = MuhasabahReport::where('student_id', $validatedData['studentId'])
            ->whereDate('time_stamp', $validatedData['tanggal'])
            ->first();

        if ($existingReport) {
            return redirect ()->back ()->with ( 'error', 'Data dengan Tanggal Tersebut sudah Dibuat.' );
        }

        if( $validatedData['shalat_sunnah'] === "Sudah" ) {
            $validatedData['shalat_sunnah'] = true;
        } else {
            $validatedData['shalat_sunnah'] = false;
        }

        if( $validatedData['subuh'] === "Sudah" ) {
            $validatedData['subuh'] = true;
        } else {
            $validatedData['subuh'] = false;
        }

        if( $validatedData['dzuhur'] === "Sudah" ) {
            $validatedData['dzuhur'] = true;
        } else {
            $validatedData['dzuhur'] = false;
        }

        if( $validatedData['ashar'] === "Sudah" ) {
            $validatedData['ashar'] = true;
        } else {
            $validatedData['ashar'] = false;
        }

        if( $validatedData['maghrib'] === "Sudah" ) {
            $validatedData['maghrib'] = true;
        } else {
            $validatedData['maghrib'] = false;
        }

        if( $validatedData['isya'] === "Sudah" ) {
            $validatedData['isya'] = true;
        } else {
            $validatedData['isya'] = false;
        }
        
        MuhasabahReport::create ( [
            'student_id' => $validatedData[ 'studentId' ],
            'time_stamp' => $validatedData[ 'tanggal' ],
            'surah_name' => $validatedData[ 'surah' ],
            'surah_ayat' => $validatedData[ 'ayat_awal' ]."-".$validatedData[ 'ayat_akhir' ],
            'sunnah_pray' => $validatedData[ 'shalat_sunnah' ],
            'subuh_pray' => $validatedData[ 'subuh' ],
            'dzuhur_pray' => $validatedData[ 'dzuhur' ],
            'ashar_pray' => $validatedData[ 'ashar' ],
            'maghrib_pray' => $validatedData[ 'maghrib' ],
            'isya_pray' => $validatedData[ 'isya' ],
            'teacher_sign' => false,
            'parent_sign' => false,
        ] );

        return redirect()->route('student.laporan-muhasabah-siswa-table.index')
            ->with('success', 'Sukses Menambahkan Data Laporan Baru.');
    }

    public function update_muhasabah_report( Request $request, $reportId )
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'surah' => 'nullable|string|max:255',
            'ayat_awal' => 'nullable|string|max:255',
            'ayat_akhir' => 'nullable|string|max:255',
            'shalat_sunnah' => 'required|string|in:Sudah,Tidak',
            'subuh' => 'required|string|in:Sudah,Tidak',
            'dzuhur' => 'required|string|in:Sudah,Tidak',
            'ashar' => 'required|string|in:Sudah,Tidak',
            'maghrib' => 'required|string|in:Sudah,Tidak',
            'isya' => 'required|string|in:Sudah,Tidak',
        ]);

        $muhasabahReport = MuhasabahReport::findOrFail($reportId);

        $existingReport = MuhasabahReport::where('student_id', $validatedData['studentId'])
            ->whereDate('time_stamp', $validatedData['tanggal'])
            ->first();

        if ($existingReport && $existingReport->id !== $muhasabahReport->id) {
            return redirect ()->back ()->with ( 'error', 'Tidak Dapat Mengganti dengan Data yang Sudah Ada' );
        }

        if( $validatedData['shalat_sunnah'] === "Sudah" ) {
            $validatedData['shalat_sunnah'] = true;
        } else {
            $validatedData['shalat_sunnah'] = false;
        }

        if( $validatedData['subuh'] === "Sudah" ) {
            $validatedData['subuh'] = true;
        } else {
            $validatedData['subuh'] = false;
        }

        if( $validatedData['dzuhur'] === "Sudah" ) {
            $validatedData['dzuhur'] = true;
        } else {
            $validatedData['dzuhur'] = false;
        }

        if( $validatedData['ashar'] === "Sudah" ) {
            $validatedData['ashar'] = true;
        } else {
            $validatedData['ashar'] = false;
        }

        if( $validatedData['maghrib'] === "Sudah" ) {
            $validatedData['maghrib'] = true;
        } else {
            $validatedData['maghrib'] = false;
        }

        if( $validatedData['isya'] === "Sudah" ) {
            $validatedData['isya'] = true;
        } else {
            $validatedData['isya'] = false;
        }

        $muhasabahReport->update([
            'time_stamp' => $validatedData[ 'tanggal' ],
            'surah_name' => $validatedData[ 'surah' ],
            'surah_ayat' => $validatedData[ 'ayat_awal' ]."-".$validatedData[ 'ayat_akhir' ],
            'sunnah_pray' => $validatedData[ 'shalat_sunnah' ],
            'subuh_pray' => $validatedData[ 'subuh' ],
            'dzuhur_pray' => $validatedData[ 'dzuhur' ],
            'ashar_pray' => $validatedData[ 'ashar' ],
            'maghrib_pray' => $validatedData[ 'maghrib' ],
            'isya_pray' => $validatedData[ 'isya' ],
            'updated_at' => now(),
        ]);

        return redirect()->route('student.laporan-muhasabah-siswa-table.index')
            ->with('success', 'Sukses Mengubah Data Laporan Baru.');
    }
    
    public function delete_muhasabah_report( Request $request, $reportId )
    {
        $muhasabahReport = MuhasabahReport::findOrFail ( $reportId );

        $muhasabahReport->delete ();

        return response ()->json ( [ 'success' => 'Data Siswa Berhasil Dihapus.' ] );
    }

    public function parent_sign_muhasabah_report( Request $request, $reportId )
    {
        $validated = $request->validate([
            'parentCode' => 'required|string'
        ]);

        $student = auth ()->user ()->student;
        $parentCode = $student->parent_code;

        if ($validated['parentCode'] !== $parentCode) {
            return response()->json([ 'error' => 'Kode Unik Salah.']);
        }

        $muhasabahReport = MuhasabahReport::findOrFail ( $reportId );
        $muhasabahReport->parent_sign = ! $muhasabahReport->parent_sign;
        $muhasabahReport->save ();

        if ( $muhasabahReport->parent_sign ) {
            return response ()->json ( [ 'success' => 'Data Sudah Ditandatangani.' ] );
        }

        return response ()->json ( [ 'success' => 'Data Tidak Jadi Ditandatangani.' ] );
    }
}
