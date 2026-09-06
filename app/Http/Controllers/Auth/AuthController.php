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

            // Verify Store account approval status
            if ($user->isStore()) {
                $store = $user->store;
                if (!$store || $store->status === 'pending') {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Ang iyong Store account ay pending pa sa pagsusuri ng Super Admin. Mangyaring maghintay para sa approval bago makapag-login.'])->onlyInput('email');
                } elseif ($store->status === 'rejected') {
                    Auth::logout();
                    $reason = $store->rejection_reason ? ": {$store->rejection_reason}" : ".";
                    return back()->withErrors(['email' => 'Na-decline ang iyong Store registration' . $reason])->onlyInput('email');
                }
            }

            // Verify Rider account approval status
            if ($user->isRider()) {
                $rider = $user->rider;
                if (!$rider || $rider->status === 'pending') {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Ang iyong Rider account ay pending pa sa pagsusuri ng Super Admin. Mangyaring maghintay para sa activation bago makapag-login.'])->onlyInput('email');
                } elseif ($rider->status === 'rejected') {
                    Auth::logout();
                    $reason = $rider->rejection_reason ? ": {$rider->rejection_reason}" : ".";
                    return back()->withErrors(['email' => 'Na-decline ang iyong Rider registration' . $reason])->onlyInput('email');
                }
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
            'barangay' => 'nullable|string',
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

        $latitude = !empty($validated['latitude']) ? (float) $validated['latitude'] : 13.9419;
        $longitude = !empty($validated['longitude']) ? (float) $validated['longitude'] : 121.1631;
        $barangay = !empty($validated['barangay']) ? $validated['barangay'] : LipaLocationService::findNearestBarangay($latitude, $longitude);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/stores/logos');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $logoPath = 'uploads/stores/logos/' . $filename;
        }

        $gcashQrPath = null;
        if ($request->hasFile('gcash_qr')) {
            $file = $request->file('gcash_qr');
            $filename = 'gcash_qr_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/stores/gcash');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $gcashQrPath = 'uploads/stores/gcash/' . $filename;
        }

        Store::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            'slug' => Str::slug($validated['store_name']) . '-' . rand(100, 999),
            'barangay' => $barangay,
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

        // Do not auto login. Redirect to login page with clear pending approval notice.
        return redirect()->route('login')->with('info', 'Matagumpay na naisumite ang iyong Store application! Sinusuri na ito ng Super Admin. Makakapag-login ka sa oras na ma-approve ang iyong account.');
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
            'address_line' => 'required|string|max:255',
            'barangay' => 'nullable|string',
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

        // Auto-detect barangay from address_line if not provided
        $barangay = $validated['barangay'] ?? null;
        if (empty($barangay)) {
            $allBarangays = LipaLocationService::getBarangayList();
            foreach ($allBarangays as $bName) {
                if (stripos($validated['address_line'], $bName) !== false) {
                    $barangay = $bName;
                    break;
                }
            }
            if (empty($barangay)) {
                $barangay = 'Marawoy';
            }
        }

        $fallbackCoords = LipaLocationService::getCoordinates($barangay);
        $latitude = !empty($validated['latitude']) ? (float) $validated['latitude'] : ($fallbackCoords['lat'] ?? 13.9419);
        $longitude = !empty($validated['longitude']) ? (float) $validated['longitude'] : ($fallbackCoords['lng'] ?? 121.1631);

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
            'barangay' => $barangay,
            'address_line' => $validated['address_line'],
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

        // Do not auto login. Redirect to login page with clear pending approval notice.
        return redirect()->route('login')->with('info', 'Matagumpay na naisumite ang iyong Rider application! Sinusuri na ito ng Super Admin. Makakapag-login ka sa oras na ma-approve at ma-activate ang iyong account.');
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
