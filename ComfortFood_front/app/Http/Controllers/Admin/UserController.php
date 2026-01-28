<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['cliente', 'restaurante', 'rol']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load(['cliente', 'restaurante', 'rol']);
        return view('admin.users.edit', compact('user'));
    }
}
