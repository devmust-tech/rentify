<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Landlord;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Enums\UserRole;
use App\Enums\UserStatus;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^(\+254|254|0)[17][0-9]{8}$/', 'unique:users'],
            'role' => ['required', 'string', 'in:agent,landlord'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => UserRole::from($request->role),
            'status' => UserStatus::ACTIVE,
            'password' => Hash::make($request->password),
        ]);

        // Create related role record
        if ($user->role === UserRole::TENANT) {
            Tenant::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
            ]);
        } elseif ($user->role === UserRole::LANDLORD) {
            Landlord::create([
                'user_id' => $user->id,
            ]);
        }
        // Agents don't need a separate record

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
