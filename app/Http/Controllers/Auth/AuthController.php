<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Store;
use App\Models\Rider;
use App\Models\RiderWallet;
use App\Services\LipaLocationService;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        $barangays = LipaLocationService::getBarangayList();
        $barangayCoords = LipaLocationService::$barangays;
        return view('auth.register', compact('barangays', 'barangayCoords'));
    }

    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect()->route('users.explore')->with('success', 'Welcome to NutriGo! Start exploring healthy food choices across Lipa City.');
    }

    public function registerStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'store_name' => 'required|string|max:255',
            'barangay' => 'required|string',
            'address_line' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'health_category' => 'required|string',
            'business_permit_no' => 'nullable|string',
            'gcash_name' => 'required|string',
            'gcash_number' => 'required|string',
            'gcash_qr' => 'nullable|image|max:3072',
            'logo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'store',
            'password' => Hash::make($validated['password']),
        ]);

        $fallbackCoords = LipaLocationService::getCoordinates($validated['barangay']);
        $latitude = !empty($validated['latitude']) ? (float) $validated['latitude'] : $fallbackCoords['lat'];
        $longitude = !empty($validated['longitude']) ? (float) $validated['longitude'] : $fallbackCoords['lng'];

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores/logos', 'public');
        }

        $gcashQrPath = null;
        if ($request->hasFile('gcash_qr')) {
            $gcashQrPath = $request->file('gcash_qr')->store('stores/gcash', 'public');
        }

        Store::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            'slug' => Str::slug($validated['store_name']) . '-' . rand(100, 999),
            'barangay' => $validated['barangay'],
            'address_line' => $validated['address_line'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'phone' => $validated['phone'],
            'health_category' => $validated['health_category'],
            'business_permit_no' => $validated['business_permit_no'] ?? null,
            'gcash_name' => $validated['gcash_name'],
            'gcash_number' => $validated['gcash_number'],
            'gcash_qr' => $gcashQrPath,
            'logo' => $logoPath,
            'status' => 'pending', // Awaits Super Admin review
        ]);

        Auth::login($user);
        return redirect()->route('store.dashboard')->with('info', 'Your store application has been submitted! Our admin team is reviewing your healthy food verification credentials.');
    }

    public function registerRider(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'vehicle_type' => 'required|string',
            'plate_number' => 'required|string|max:50',
            'license_number' => 'required|string|max:50',
            'barangay' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'license_image' => 'nullable|image|max:3072',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'rider',
            'password' => Hash::make($validated['password']),
        ]);

        $fallbackCoords = LipaLocationService::getCoordinates($validated['barangay']);
        $latitude = !empty($validated['latitude']) ? (float) $validated['latitude'] : $fallbackCoords['lat'];
        $longitude = !empty($validated['longitude']) ? (float) $validated['longitude'] : $fallbackCoords['lng'];

        $licensePath = null;
        if ($request->hasFile('license_image')) {
            $licensePath = $request->file('license_image')->store('riders/licenses', 'public');
        }

        $rider = Rider::create([
            'user_id' => $user->id,
            'vehicle_type' => $validated['vehicle_type'],
            'plate_number' => $validated['plate_number'],
            'license_number' => $validated['license_number'],
            'license_image' => $licensePath,
            'phone' => $validated['phone'],
            'barangay' => $validated['barangay'],
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'status' => 'pending',
            'is_online' => true,
        ]);

        RiderWallet::create([
            'rider_id' => $rider->id,
            'balance' => 0.00,
            'total_earnings' => 0.00,
            'pending_payout' => 0.00,
        ]);

        Auth::login($user);
        return redirect()->route('riders.dashboard')->with('info', 'Your rider application has been submitted! Awaiting Admin verification before taking delivery trips.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    public function quickLogin(string $role)
    {
        $email = match($role) {
            'admin' => 'admin@nutrigo.ph',
            'store' => 'greenbites@nutrigo.ph',
            'rider' => 'rider.juan@nutrigo.ph',
            'customer' => 'customer@nutrigo.ph',
            default => 'customer@nutrigo.ph',
        };

        $user = User::where('email', $email)->first();
        if ($user) {
            Auth::login($user);
            return $this->redirectBasedOnRole($user);
        }

        return redirect()->route('login');
    }

    protected function redirectBasedOnRole(User $user)
    {
        return match($user->role) {
            'admin' => redirect()->route('superadmin.dashboard'),
            'store' => redirect()->route('store.dashboard'),
            'rider' => redirect()->route('riders.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
