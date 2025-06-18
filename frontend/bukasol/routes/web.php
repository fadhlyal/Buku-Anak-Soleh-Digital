<?php

use App\Livewire\Login;
use App\Livewire\Dashboard;
use App\Livewire\Admin\StudentTable;
use App\Livewire\Admin\TeacherTable;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminDashboard;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;

use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentPrayerGradeController;
use App\Http\Controllers\StudentPrayerRecitationGradeController;
use App\Http\Controllers\StudentActivityNotesController;
use App\Http\Controllers\StudentReadActivityController;
use App\Http\Controllers\StudentJuzReportController;
use App\Http\Controllers\StudentMuhasabahReportController;
use App\Http\Controllers\StudentViolationReportController;

use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherPrayerGradeController;
use App\Http\Controllers\TeacherPrayerRecitationGradeController;
use App\Http\Controllers\TeacherActivityNotesController;
use App\Http\Controllers\TeacherReadActivityController;
use App\Http\Controllers\TeacherMuhasabahReportController;
use App\Http\Controllers\TeacherViolationReportController;
use App\Http\Controllers\TeacherJuzReportController;

Route::get ( '/', function ()
{
    return redirect ()->route ( 'login.index' );
} );

Route::get (
    '/login',
    [ UserController::class, 'login_index' ]
)
    ->middleware ( 'Authenticated' )
    ->name ( 'login.index' );

Route::post (
    '/login',
    [ UserController::class, 'login' ]
)
    ->middleware ( 'Authenticated' )
    ->name ( 'login' );

Route::post (
    '/logout',
    [ UserController::class, 'logout' ]
)->name ( 'logout' );

Route::get (
    '/dashboard',
    [ DashboardController::class, 'index' ]
)
    ->middleware ( 'auth' )
    ->name ( 'dashboard.index' );

Route::prefix ( 'student-dashboard' )
    ->middleware ( 'Student' )
    ->group ( function ()
    {
        Route::get (
            '/',
            [ StudentDashboardController::class, 'index' ]
        )
            ->name ( 'dashboard.student.index' );

        // Student Muhasabah Report
        Route::get (
            '/laporan-muhasabah-siswa',
            [ StudentMuhasabahReportController::class, 'index_table' ]
        )
            ->name ( 'student.laporan-muhasabah-siswa-table.index' );

        Route::get (
            '/laporan-muhasabah-siswa-detail/{id}',
            [ StudentMuhasabahReportController::class, 'index_detail' ]
        )
            ->name ( 'student.laporan-muhasabah-siswa-detail.index' );

        Route::get (
            '/laporan-muhasabah-siswa-add',
            [ StudentMuhasabahReportController::class, 'index_add_report' ]
        )
            ->name ( 'student.laporan-muhasabah-siswa-add.index' );

        Route::get (
            '/laporan-muhasabah-siswa-edit/{id}',
            [ StudentMuhasabahReportController::class, 'index_edit_report' ]
        )
            ->name ( 'student.laporan-muhasabah-siswa-edit.index' );

        Route::post (
            '/muhasabah-report',
            [ StudentMuhasabahReportController::class, 'store_muhasabah_report' ]
        )
            ->name ( 'muhasabah-report.store' );

        Route::put (
            '/muhasabah-report/{id}',
            [ StudentMuhasabahReportController::class, 'update_muhasabah_report' ]
        )
            ->name ( 'muhasabah-report.update' );

        Route::delete (
            '/muhasabah-report/{id}',
            [ StudentMuhasabahReportController::class, 'delete_muhasabah_report' ]
        )
            ->name ( 'muhasabah-report.delete' );

        Route::put (
            '/muhasabah-report/parent-sign/{id}',
            [ StudentMuhasabahReportController::class, 'parent_sign_muhasabah_report' ]
        )
            ->name ( 'muhasabah-report.parent-sign' );

        // Student Violation Report
        Route::get (
            '/laporan-pelanggaran-siswa',
            [ StudentViolationReportController::class, 'index_table' ]
        )
            ->name ( 'student.laporan-pelanggaran-siswa-table.index' );

        Route::get (
            '/laporan-pelanggaran-siswa-detail/{id}',
            [ StudentViolationReportController::class, 'index_detail' ]
        )
            ->name ( 'student.laporan-pelanggaran-siswa-detail.index' );

        // Student Juz Report
        Route::get (
            '/laporan-juz{juzNumber}-siswa',
            [ StudentJuzReportController::class, 'index_table' ]
        )
            ->name ( 'student.laporan-juz-siswa-table.index' );

        // Student Prayer Grade
        Route::get (
            '/nilai-uji-gerakan-siswa',
            [ StudentPrayerGradeController::class, 'index' ]
        )
            ->name ( 'student.nilai-uji-gerakan-siswa-table.index' );

        Route::put (
            '/prayer-grade/parent-sign/{id}',
            [ StudentPrayerGradeController::class, 'parent_sign_prayer_grade' ]
        )
            ->name ( 'prayer-grade.parent-sign' );

        // Student Prayer Recitation Grade
        Route::get (
            '/nilai-uji-bacaan-siswa',
            [ StudentPrayerRecitationGradeController::class, 'index' ]
        )
            ->name ( 'student.nilai-uji-bacaan-siswa-table.index' );

        Route::put (
            '/prayer-recitation-grade/parent-sign/{id}',
            [ StudentPrayerRecitationGradeController::class, 'parent_sign_prayer_recitation_grade' ]
        )
            ->name ( 'prayer-recitation-grade.parent-sign' );

        // Student Daily Activity Notes
        Route::get (
            '/catatan-harian-siswa',
            [ StudentActivityNotesController::class, 'index_table' ]
        )
            ->name ( 'student.catatan-harian-siswa-table.index' );

        Route::get (
            '/catatan-harian-siswa-detail/{id}',
            [ StudentActivityNotesController::class, 'index_detail' ]
        )
            ->name ( 'student.catatan-harian-siswa-detail.index' );

        Route::get (
            '/catatan-harian-siswa-add',
            [ StudentActivityNotesController::class, 'index_add_notes' ]
        )
            ->name ( 'student.catatan-harian-siswa-add.index' );

        Route::get (
            '/catatan-harian-siswa-edit/{id}',
            [ StudentActivityNotesController::class, 'index_edit_notes' ]
        )
            ->name ( 'student.catatan-harian-siswa-edit.index' );

        Route::post (
            '/activity-notes',
            [ StudentActivityNotesController::class, 'store_activity_notes' ]
        )
            ->name ( 'activity-notes.store' );
        
        Route::put (
            '/activity-notes/{id}',
            [ StudentActivityNotesController::class, 'update_activity_notes' ]
        )
            ->name ( 'activity-notes.update' );

        Route::delete (
            '/activity-notes/{id}',
            [ StudentActivityNotesController::class, 'delete_activity_notes' ]
        )
            ->name ( 'activity-notes.delete' );

        // Student Reading Activity Notes
        Route::get (
            '/aktivitas-membaca-siswa',
            [ StudentReadActivityController::class, 'index_table' ]
        )
            ->name ( 'student.aktivitas-membaca-siswa-table.index' );

        Route::get (
            '/aktivitas-membaca-siswa-add',
            [ StudentReadActivityController::class, 'index_add_activity' ]
        )
            ->name ( 'student.aktivitas-membaca-siswa-add.index' );

        Route::get (
            '/aktivitas-membaca-siswa-edit/{id}',
            [ StudentReadActivityController::class, 'index_edit_activity' ]
        )
            ->name ( 'student.aktivitas-membaca-siswa-edit.index' );

        Route::post (
            '/reading-activity',
            [ StudentReadActivityController::class, 'store_reading_activity' ]
        )
            ->name ( 'reading-activity.store' );

        Route::put (
            '/reading-activity/{id}',
            [ StudentReadActivityController::class, 'update_reading_activity' ]
        )
            ->name ( 'reading-activity.update' );

        Route::delete (
            '/reading-activity/{id}',
            [ StudentReadActivityController::class, 'delete_reading_activity' ]
        )
            ->name ( 'reading-activity.delete' );

        Route::put (
            '/reading-activity/parent-sign/{id}',
            [ StudentReadActivityController::class, 'parent_sign_reading_activity' ]
        )
            ->name ( 'reading-activity.parent-sign' );
    } );

Route::prefix ( 'teacher-dashboard' )
    ->middleware ( 'Teacher' )
    ->group ( function ()
    {
        Route::get (
            '/',
            [ TeacherDashboardController::class, 'index' ]
        )
            ->name ( 'dashboard.teacher.index' );

        // Teacher Juz Report
        Route::get (
            '/laporan-bacaan-juz{juzNumber}',
            [ TeacherJuzReportController::class, 'index_teacher_juz' ]
        )
            ->name ( 'teacher.laporan-bacaan-juz-siswa-table.index' );

        Route::get (
            '/laporan-bacaan-juz{juzNumber}-siswa/{id}',
            [ TeacherJuzReportController::class, 'index_student_juz' ]
        )
            ->name ( 'teacher.laporan-bacaan-juz-siswa.index' );

        Route::get (
            '/laporan-juz{juzNumber}-siswa-add/{id}',
            [ TeacherJuzReportController::class, 'index_add_report' ]
        )
            ->name ( 'teacher.laporan-juz-siswa-add.index' );

        Route::get (
            '/laporan-juz{juzNumber}-siswa-edit/{id}',
            [ TeacherJuzReportController::class, 'index_edit_report' ]
        )
            ->name ( 'teacher.laporan-juz-siswa-edit.index' );

        Route::post (
            '/juz-report',
            [ TeacherJuzReportController::class, 'store_juz_report' ]
        )
            ->name ( 'juz-report.store' );

        Route::put (
            '/juz-report/{id}',
            [ TeacherJuzReportController::class, 'update_juz_report' ]
        )
            ->name ( 'juz-report.update' );

        Route::delete (
            '/juz-report/{id}',
            [ TeacherJuzReportController::class, 'delete_juz_report' ]
        )
            ->name ( 'juz-report.delete' );

        Route::put (
            '/juz-report/teacher-sign/{id}',
            [ TeacherJuzReportController::class, 'teacher_sign_juz_report' ]
        )
            ->name ( 'juz-report.teacher-sign' );

        Route::put (
            '/juz{juzNumber}-report/teacher-sign-all/{id}',
            [ TeacherJuzReportController::class, 'juz_report_approveAll' ]
        )
            ->name ( 'juz-report.teacher-sign-all' );

        // Teacher Violation Report
        Route::get (
            '/laporan-pelanggaran-siswa',
            [ TeacherViolationReportController::class, 'index_teacher' ]
        )
            ->name ( 'teacher.laporan-pelanggaran-siswa-table.index' );

        Route::get (
            '/laporan-pelanggaran-siswa/{id}',
            [ TeacherViolationReportController::class, 'index_student' ]
        )
            ->name ( 'teacher.laporan-pelanggaran-siswa.index' );

        Route::get (
            '/laporan-pelanggaran-siswa-detail/{id}',
            [ TeacherViolationReportController::class, 'index_detail' ]
        )
            ->name ( 'teacher.laporan-pelanggaran-siswa-detail.index' );

        Route::get (
            '/laporan-pelanggaran-siswa-add/{id}',
            [ TeacherViolationReportController::class, 'index_add_report' ]
        )
            ->name ( 'teacher.laporan-pelanggaran-siswa-add.index' );

        Route::get (
            '/laporan-pelanggaran-siswa-edit/{id}',
            [ TeacherViolationReportController::class, 'index_edit_report' ]
        )
            ->name ( 'teacher.laporan-pelanggaran-siswa-edit.index' );

        Route::post (
            '/violation-report',
            [ TeacherViolationReportController::class, 'store_violation_report' ]
        )
            ->name ( 'violation-report.store' );

        Route::put (
            '/violation-report/{id}',
            [ TeacherViolationReportController::class, 'update_violation_report' ]
        )
            ->name ( 'violation-report.update' );

        Route::delete (
            '/violation-report/{id}',
            [ TeacherViolationReportController::class, 'delete_violation_report' ]
        )
            ->name ( 'violation-report.delete' );

        Route::put (
            '/violation-report/teacher-sign/{id}',
            [ TeacherViolationReportController::class, 'teacher_sign_violation_report' ]
        )
            ->name ( 'violation-report.teacher-sign' );

        Route::put (
            '/violation-report/teacher-sign-all/{id}',
            [ TeacherViolationReportController::class, 'violation_report_approveAll' ]
        )
            ->name ( 'violation-report.teacher-sign-all' );

        // Teacher Muhasabah Report
        Route::get (
            '/laporan-muhasabah-siswa',
            [ TeacherMuhasabahReportController::class, 'index_teacher' ]
        )
            ->name ( 'teacher.laporan-muhasabah-siswa-table.index' );

        Route::get (
            '/laporan-muhasabah-siswa/{id}',
            [ TeacherMuhasabahReportController::class, 'index_student' ]
        )
            ->name ( 'teacher.laporan-muhasabah-siswa.index' );

        Route::get (
            '/laporan-muhasabah-siswa-detail/{id}',
            [ TeacherMuhasabahReportController::class, 'index_detail' ]
        )
            ->name ( 'teacher.laporan-muhasabah-siswa-detail.index' );

        Route::put (
            '/muhasabah-report/teacher-sign/{id}',
            [ TeacherMuhasabahReportController::class, 'teacher_sign_muhasabah_report' ]
        )
            ->name ( 'muhasabah-report.teacher-sign' );

        Route::put (
            '/muhasabah-report/teacher-sign-all/{id}',
            [ TeacherMuhasabahReportController::class, 'muhasabah_report_approveAll' ]
        )
            ->name ( 'muhasabah-report.teacher-sign-all' );

        // Teacher Prayer Grade
        Route::get (
            '/nilai-uji-gerakan-siswa',
            [ TeacherPrayerGradeController::class, 'index_teacher' ]
        )
            ->name ( 'teacher.nilai-uji-gerakan-siswa-table.index' );

        Route::get (
            '/nilai-uji-gerakan-siswa-detail/{id}',
            [ TeacherPrayerGradeController::class, 'index_student' ]
        )
            ->name ( 'teacher.nilai-uji-gerakan-siswa-detail.index' );

        Route::get (
            '/nilai-uji-gerakan-siswa-add/{id}',
            [ TeacherPrayerGradeController::class, 'index_add_grade' ]
        )
            ->name ( 'teacher.nilai-uji-gerakan-siswa-add.index' );

        Route::get (
            '/nilai-uji-gerakan-siswa-edit/{id}',
            [ TeacherPrayerGradeController::class, 'index_edit_grade' ]
        )
            ->name ( 'teacher.nilai-uji-gerakan-siswa-edit.index' );

        Route::post (
            '/prayer-grade',
            [ TeacherPrayerGradeController::class, 'store_prayer_grade' ]
        )
            ->name('prayer-grade.store');

        Route::put (
            '/prayer-grade/{id}',
            [ TeacherPrayerGradeController::class, 'update_prayer_grade' ]
        )
            ->name ( 'prayer-grade.update' );

        Route::delete (
            '/prayer-grade/{id}',
            [ TeacherPrayerGradeController::class, 'delete_prayer_grade' ]
        )
            ->name ( 'prayer-grade.delete' );

        Route::put (
            '/prayer-grade/teacher-sign/{id}',
            [ TeacherPrayerGradeController::class, 'teacher_sign_prayer_grade' ]
        )
            ->name ( 'prayer-grade.teacher-sign' );

        Route::put (
            '/prayer-grade/teacher-sign-all/{id}',
            [ TeacherPrayerGradeController::class, 'prayer_grade_approveAll' ]
        )
            ->name ( 'prayer-grade.teacher-sign-all' );

        // Teacher Prayer Recitation Grade
        Route::get (
            '/nilai-uji-bacaan-siswa',
            [ TeacherPrayerRecitationGradeController::class, 'index_teacher' ]
        )
            ->name ( 'teacher.nilai-uji-bacaan-siswa-table.index' );

        Route::get (
            '/nilai-uji-bacaan-siswa-detail/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'index_student' ]
        )
            ->name ( 'teacher.nilai-uji-bacaan-siswa-detail.index' );

        Route::get (
            '/nilai-uji-bacaan-siswa-add/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'index_add_grade' ]
        )
            ->name ( 'teacher.nilai-uji-bacaan-siswa-add.index' );

        Route::get (
            '/nilai-uji-bacaan-siswa-edit/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'index_edit_grade' ]
        )
            ->name ( 'teacher.nilai-uji-bacaan-siswa-edit.index' );

        Route::post (
            '/prayer-recitation-grade',
            [ TeacherPrayerRecitationGradeController::class, 'store_prayer_recitation_grade' ]
        )
            ->name('prayer-recitation.store');

        Route::put (
            '/prayer-recitation-grade/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'update_prayer_recitation_grade' ]
        )
            ->name ( 'prayer-recitation-grade.update' );

        Route::delete (
            '/prayer-recitation-grade/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'delete_prayer_recitation_grade' ]
        )
            ->name ( 'prayer-recitation-grade.delete' );

        Route::put (
            '/prayer-recitation-grade/teacher-sign/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'teacher_sign_prayer_recitation_grade' ]
        )
            ->name ( 'prayer-recitation-grade.teacher-sign' );

        Route::put (
            '/prayer-recitation-grade/teacher-sign-all/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'prayer_recitation_grade_approveAll' ]
        )
            ->name ( 'prayer-recitation-grade.teacher-sign-all' );

        // Teacher Notes Activity
        Route::get (
            '/catatan-harian-siswa',
            [ TeacherActivityNotesController::class, 'index_teacher' ]
        )
            ->name ( 'teacher.catatan-harian-siswa-table.index' );

        Route::get (
            '/catatan-harian-siswa/{id}',
            [ TeacherActivityNotesController::class, 'index_student' ]
        )
            ->name ( 'teacher.catatan-harian-siswa.index' );

        Route::get (
            '/catatan-harian-siswa-detail/{id}',
            [ TeacherActivityNotesController::class, 'index_detail' ]
        )
            ->name ( 'teacher.catatan-harian-siswa-detail.index' );

        Route::get (
            '/catatan-harian-siswa/pertanyaan-orangtua/{id}',
            [ TeacherActivityNotesController::class, 'index_question' ]
        )
            ->name ( 'teacher.jawaban-pertanyaan-orangtua.index' );

        Route::put (
            '/activity-notes/{id}',
            [ TeacherActivityNotesController::class, 'answer_parent_question' ]
        )
            ->name ( 'activity-notes-parent-answer.update' );

        Route::put (
            '/activity-notes/teacher-sign/{id}',
            [ TeacherActivityNotesController::class, 'teacher_sign_activity_notes' ]
        )
            ->name ( 'activity-notes.teacher-sign' );

        Route::put (
            '/activity-notes/teacher-sign-all/{id}',
            [ TeacherActivityNotesController::class, 'activity_notes_approveAll' ]
        )
            ->name ( 'activity-notes.teacher-sign-all' );

        // Teacher Read Activity
        Route::get (
            '/aktivitas-membaca-siswa',
            [ TeacherReadActivityController::class, 'index_teacher' ]
        )
            ->name ( 'teacher.aktivitas-membaca-siswa-table.index' );

        Route::get (
            '/aktivitas-membaca-siswa/{id}',
            [ TeacherReadActivityController::class, 'index_student' ]
        )
            ->name ( 'teacher.aktivitas-membaca-siswa.index' );

        Route::put (
            '/read-activity/teacher-sign/{id}',
            [ TeacherReadActivityController::class, 'teacher_sign_read_activity' ]
        )
            ->name ( 'read-activity.teacher-sign' );

        Route::put (
            '/read-activity/teacher-sign-all/{id}',
            [ TeacherReadActivityController::class, 'read_activity_approveAll' ]
        )
            ->name ( 'read-activity.teacher-sign-all' );
    } );

Route::prefix ( 'admin-dashboard' )
    ->middleware ( 'Admin' )
    ->group ( function ()
    {
        Route::get (
            '/',
            [ AdminDashboardController::class, 'index' ]
        )
            ->name ( 'dashboard.admin.index' );

        Route::get (
            '/student-table',
            [ AdminDashboardController::class, 'student_table_index' ]
        )
            ->name ( 'admin.student-table.index' );

        Route::get (
            '/teacher-table',
            [ AdminDashboardController::class, 'teacher_table_index' ]
        )
            ->name ( 'admin.teacher-table.index' );

        Route::get (
            '/student-add',
            [ AdminDashboardController::class, 'student_add_index' ]
        )
            ->name ( 'admin.student-add.index' );

        Route::get (
            '/teacher-add',
            [ AdminDashboardController::class, 'teacher_add_index' ]
        )
            ->name ( 'admin.teacher-add.index' );

        Route::get (
            '/student-detail/{id}',
            [ AdminDashboardController::class, 'student_detail_index' ]
        )
            ->name ( 'admin.student-detail.index' );

        Route::get (
            '/teacher-detail/{id}',
            [ AdminDashboardController::class, 'teacher_detail_index' ]
        )
            ->name ( 'admin.teacher-detail.index' );

        Route::get (
            '/export-akun-siswa',
            [ AdminDashboardController::class, 'student_account_pdf' ]
        )
            ->name ( 'student-account.convert-pdf' );

        Route::get (
            '/export-akun-guru',
            [ AdminDashboardController::class, 'teacher_account_pdf' ]
        )
            ->name ( 'teacher-account.convert-pdf' );

        Route::post (
            '/import-akun-siswa',
            [ AdminDashboardController::class, 'student_import_account' ]
        )
            ->name ( 'student-account.import-excel' );

        Route::post (
            '/import-akun-guru',
            [ AdminDashboardController::class, 'teacher_import_account' ]
        )
            ->name ( 'teacher-account.import-excel' );
    } );


// Rute untuk menangani Paginasi Tabel
Route::middleware ( 'auth' )
    ->group ( function ()
    {
        Route::post (
            '/teacher/fetchData',
            [ AdminDashboardController::class, 'fetchData_teacher' ]
        )->name ( 'teacher.fetchData' );

        Route::post (
            '/student/fetchData',
            [ AdminDashboardController::class, 'fetchData_student' ]
        )->name ( 'student.fetchData' );

        // Muhasabah Report
        Route::post (
            '/laporan-muhasabah/fetchData',
            [ TeacherMuhasabahReportController::class, 'fetchData_laporan_muhasabah_by_nama_kelas' ]
        )->name ( 'laporan-muhasabah.fetchData' );

        Route::post (
            '/laporan-muhasabah/fetchData/{id}',
            [ TeacherMuhasabahReportController::class, 'fetchData_laporan_muhasabah_by_id_siswa' ]
        )->name ( 'laporan-muhasabah-siswa.fetchData' );

        Route::post (
            '/siswa-laporan-muhasabah/fetchData',
            [ StudentMuhasabahReportController::class, 'fetchData_laporan_muhasabah_by_id_siswa' ]
        )->name ( 'siswa.laporan-muhasabah.fetchData' );

        Route::get (
            '/muhasabah-report/{id}',
            [ TeacherMuhasabahReportController::class, 'muhasabah_report_pdf' ]
        )
            ->name ( 'muhasabah-report.convert-pdf' );

        // Violation Report
        Route::post (
            '/laporan-pelanggaran/fetchData',
            [ TeacherViolationReportController::class, 'fetchData_laporan_pelanggaran_by_nama_kelas' ]
        )->name ( 'laporan-pelanggaran.fetchData' );

        Route::post (
            '/laporan-pelanggaran/fetchData/{id}',
            [ TeacherViolationReportController::class, 'fetchData_laporan_pelanggaran_by_id_siswa' ]
        )->name ( 'laporan-pelanggaran-siswa.fetchData' );

        Route::post (
            '/siswa-laporan-pelanggaran/fetchData',
            [ StudentViolationReportController::class, 'fetchData_laporan_pelanggaran_by_id_siswa' ]
        )->name ( 'siswa.laporan-pelanggaran-siswa.fetchData' );

        Route::get (
            '/violation-report/{id}',
            [ TeacherViolationReportController::class, 'violation_report_pdf' ]
        )
            ->name ( 'violation-report.convert-pdf' );

        // Juz Report
        Route::post (
            '/laporan-juz{juzNumber}/fetchData',
            [ TeacherJuzReportController::class, 'fetchData_laporan_juz_by_nama_kelas' ]
        )->name ( 'laporan-juz.fetchData' );

        Route::post (
            '/laporan-juz{juzNumber}/fetchData/{id}',
            [ TeacherJuzReportController::class, 'fetchData_laporan_juz_by_id_siswa' ]
        )->name ( 'laporan-juz-siswa.fetchData' );

        Route::post (
            '/siswa-laporan-juz{juzNumber}/fetchData',
            [ StudentJuzReportController::class, 'fetchData_laporan_juz_by_id_siswa' ]
        )->name ( 'siswa.laporan-juz-siswa.fetchData' );

        Route::get (
            '/juz{juzNumber}-report/{id}',
            [ TeacherJuzReportController::class, 'juz_report_pdf' ]
        )
            ->name ( 'juz-report.convert-pdf' );

        // Prayer Grade
        Route::post (
            '/nilai-uji-gerakan/fetchData',
            [ TeacherPrayerGradeController::class, 'fetchData_nilai_uji_gerakan_by_nama_kelas' ]
        )->name ( 'nilai_uji_gerakan.fetchData' );

        Route::post (
            '/nilai-uji-gerakan-detail/fetchData/{id}',
            [ TeacherPrayerGradeController::class, 'fetchData_nilai_uji_gerakan_by_id_siswa' ]
        )->name ( 'nilai_uji_gerakan_detail.fetchData' );

        Route::post (
            '/siswa-nilai-uji-gerakan/fetchData',
            [ StudentPrayerGradeController::class, 'fetchData_nilai_uji_gerakan_by_id_siswa' ]
        )->name ( 'siswa.nilai_uji_gerakan.fetchData' );

        Route::get (
            '/prayer-grade-report/{id}',
            [ TeacherPrayerGradeController::class, 'prayer_grade_pdf' ]
        )
            ->name ( 'prayer-grade.convert-pdf' );

        // Prayer Recitation Grade
        Route::post (
            '/nilai-uji-bacaan/fetchData',
            [ TeacherPrayerRecitationGradeController::class, 'fetchData_nilai_uji_bacaan_by_nama_kelas' ]
        )->name ( 'nilai_uji_bacaan.fetchData' );

        Route::post (
            '/nilai-uji-bacaan-detail/fetchData/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'fetchData_nilai_uji_bacaan_by_id_siswa' ]
        )->name ( 'nilai_uji_bacaan_detail.fetchData' );

        Route::post (
            '/siswa-nilai-uji-bacaan/fetchData',
            [ StudentPrayerRecitationGradeController::class, 'fetchData_nilai_uji_bacaan_by_id_siswa' ]
        )->name ( 'siswa.nilai_uji_bacaan.fetchData' );

        Route::get (
            '/prayer-recitation-grade-report/{id}',
            [ TeacherPrayerRecitationGradeController::class, 'prayer_recitation_grade_pdf' ]
        )
            ->name ( 'prayer-recitation-grade.convert-pdf' );

        // Activity Notes
        Route::post (
            '/siswa-catatan-harian/fetchData',
            [ StudentActivityNotesController::class, 'fetchData_catatan_harian_by_id_siswa' ]
        )->name ( 'siswa.catatan-harian.fetchData' );

        Route::post (
            '/catatan-harian/fetchData',
            [ TeacherActivityNotesController::class, 'fetchData_catatan_harian_by_nama_kelas' ]
        )->name ( 'catatan-harian.fetchData' );

        Route::post (
            '/catatan-harian/fetchData/{id}',
            [ TeacherActivityNotesController::class, 'fetchData_catatan_harian_by_id_siswa' ]
        )->name ( 'catatan-harian-detail.fetchData' );

        Route::get (
            '/activity-notes-report/{id}',
            [ TeacherActivityNotesController::class, 'activity_notes_pdf' ]
        )
            ->name ( 'activity-notes.convert-pdf' );

        // Reading Activity
        Route::post (
            '/siswa-aktivitas-membaca/fetchData',
            [ StudentReadActivityController::class, 'fetchData_aktivitas_membaca_by_id_siswa' ]
        )->name ( 'siswa.aktivitas-membaca.fetchData' );

        Route::post (
            '/aktivitas-membaca/fetchData',
            [ TeacherReadActivityController::class, 'fetchData_aktivitas_membaca_by_nama_kelas' ]
        )->name ( 'aktivitas-membaca.fetchData' );

        Route::post (
            '/aktivitas-membaca/fetchData/{id}',
            [ TeacherReadActivityController::class, 'fetchData_aktivitas_membaca_by_id_siswa' ]
        )->name ( 'aktivitas-membaca-siswa.fetchData' );

        Route::get (
            '/reading-activity-report/{id}',
            [ TeacherReadActivityController::class, 'reading_activity_pdf' ]
        )
            ->name ( 'reading-activity.convert-pdf' );

    } );

// Rute untuk menangani proses CRUD data Guru
Route::middleware ( 'auth' )
    ->group ( function ()
    {
        Route::post (
            '/teachers',
            [ TeacherController::class, 'store' ]
        )->name ( 'teachers.store' );

        Route::get (
            '/teachers/{teacher}',
            [ TeacherController::class, 'show' ]
        )->name ( 'teachers.show' );

        Route::put (
            '/teachers/{teacher}',
            [ TeacherController::class, 'update' ]
        )->name ( 'teachers.update' );

        Route::delete (
            '/teachers/{teacher}',
            [ TeacherController::class, 'destroy' ]
        )->name ( 'teachers.destroy' );

    } );

// Rute untuk menangani proses CRUD data Siswa
Route::middleware ( 'auth' )
    ->group ( function ()
    {
        Route::post (
            '/students',
            [ StudentController::class, 'store' ]
        )->name ( 'students.store' );

        Route::get (
            '/students/{student}',
            [ StudentController::class, 'show' ]
        )->name ( 'students.show' );

        Route::put (
            '/students/{student}',
            [ StudentController::class, 'update' ]
        )->name ( 'students.update' );

        Route::delete (
            '/students/{student}',
            [ StudentController::class, 'destroy' ]
        )->name ( 'students.destroy' );
    } );

// Route untuk menangani proses ganti password
Route::get (
    '/change-password',
    [ UserController::class, 'changePassword_index' ]
)
    ->middleware ( 'auth' )
    ->name ( 'change-password.index' );

Route::post (
    '/change-password',
    [ UserController::class, 'changePassword' ]
)
    ->middleware ( 'auth' )
    ->name ( 'change-password' );