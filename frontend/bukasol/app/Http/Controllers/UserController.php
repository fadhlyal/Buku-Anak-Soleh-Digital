<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function login_index ()
    {
        return view ( 'auth.login', [ 
            'page' => 'Login'
        ] );
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login ( Request $request )
    {
        // Validate input
        $request->validate ( [ 
            'username' => 'required|string',
            'password' => 'required|string',
        ] );

        // Find user by username
        $user = User::where ( 'username', $request->username )->first ();

        // Check if user exists and verify password
        if ( $user && Hash::check ( $request->password, $user->password ) )
        {
            // Authenticate the user
            Auth::login ( $user );

            // Redirect to the dashboard
            return redirect ()->route ( 'dashboard.index' )->with ( 'success', 'Login Berhasil' );
        }

        // If authentication fails, redirect back with an error message
        return redirect ()->back ()->with ( 'error', 'Username atau Password Salah' );
    }

    public function changePassword_index ()
    {
        $auth = auth ()->user ();

        return view ( 'change-password', [ 
            'role' => $auth->role,
            'name' => $auth->name,

            'page' => "Change Password"
        ] );
    }

    /**
     * Handle the change password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword ( Request $request )
    {
        // Validate the input
        $request->validate ( [ 
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi Password Baru Tidak Sesuai'
        ]);

        // Check if the current password is correct
        if ( ! Hash::check ( $request->current_password, Auth::user ()->password ) )
        {
            return redirect ()->back ()->with ( 'error', 'Password Sekarang Salah' );
        }

        // Update the user's password
        $user           = Auth::user ();
        $user->password = Hash::make ( $request->new_password );
        $user->save ();

        return redirect()->route('admin.student-table.index')
            ->with('success', 'Password Berhasil Diubah');
    }

    /**
     * Log the user out (Invalidate the session).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout ()
    {
        // Log the user out using Auth facade
        Auth::logout ();

        // Redirect to the home page
        return redirect ( '/' );
    }
}
