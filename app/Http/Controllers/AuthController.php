<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Description
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231025 - Created
     */
    public function index()
    {
        return view('pages.auth.login');
    }
}
