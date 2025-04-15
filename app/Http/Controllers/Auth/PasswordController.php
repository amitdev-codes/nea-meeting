<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(Request $request): View
    {
        $breadcrumb = [
            'items' => [
                [
                    'label' => 'Profile',
                    'route' => 'dashboard',
                ],
                [
                    'label' => 'Change Password',
                ],
            ],
            'title' => __('menu.change_password'),
        ];

        return view('pages.account.change-password', ['breadcrumb' => $breadcrumb]);
        // return view('pages.account.change-password');
    }

    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('notification', $this->successNotification('notification.success_update', 'menu.change_password'));
    }
}
