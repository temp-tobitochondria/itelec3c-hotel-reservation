<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $totalReservations = Reservation::count();
        $todayCheckIns = Reservation::whereDate('check_in_date', $today)->count();
        $todayCheckOuts = Reservation::whereDate('check_out_date', $today)->count();
        $totalRevenue = (float) Reservation::sum('total_price');

        $roomsTotal = Room::count();
        $roomsAvailable = Room::where('availability_status', 'available')->count();
        $roomsUnavailable = max($roomsTotal - $roomsAvailable, 0);

        $myReservations = Reservation::where('user_id', $user->user_id)->count();

        return view('dashboard.index', compact(
            'today',
            'totalReservations',
            'todayCheckIns',
            'todayCheckOuts',
            'totalRevenue',
            'roomsTotal',
            'roomsAvailable',
            'roomsUnavailable',
            'myReservations'
        ));
    }

    public function users()
    {
        $users = User::query()
            ->orderBy('user_id')
            ->get();

        return view('dashboard.users', compact('users'));
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'full_name' => $validated['full_name'],
            'role' => $validated['role'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('dashboard.users')->with('success', 'User added.');
    }

    public function usersUpdate(Request $request, int $userId)
    {
        $user = User::query()->where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->user_id, 'user_id')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->fill([
            'full_name' => $validated['full_name'],
            'role' => $validated['role'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('dashboard.users')->with('success', 'User updated.');
    }

    public function usersDestroy(int $userId)
    {
        User::query()->where('user_id', $userId)->delete();

        return redirect()->route('dashboard.users')->with('success', 'User deleted.');
    }

    public function logs()
    {
        $logs = UserLog::query()
            ->with('user')
            ->orderByDesc('date_time')
            ->limit(200)
            ->get();

        return view('dashboard.logs', compact('logs'));
    }

    public function rooms()
    {
        $rooms = Room::query()
            ->orderBy('room_number')
            ->get();

        return view('dashboard.rooms', compact('rooms'));
    }

    public function roomsStore(Request $request)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:255', 'unique:rooms,room_number'],
            'room_type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'availability_status' => ['required', 'string', 'max:255'],
        ]);

        Room::create($validated);

        return redirect()->route('dashboard.rooms')->with('success', 'Room added.');
    }

    public function roomsUpdate(Request $request, int $roomId)
    {
        $room = Room::query()->where('room_id', $roomId)->firstOrFail();

        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:255', Rule::unique('rooms', 'room_number')->ignore($room->room_id, 'room_id')],
            'room_type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'availability_status' => ['required', 'string', 'max:255'],
        ]);

        $room->update($validated);

        return redirect()->route('dashboard.rooms')->with('success', 'Room updated.');
    }

    public function roomsDestroy(int $roomId)
    {
        Room::query()->where('room_id', $roomId)->delete();

        return redirect()->route('dashboard.rooms')->with('success', 'Room deleted.');
    }

    public function amenities()
    {
        $amenities = Amenity::query()
            ->orderBy('amenity_name')
            ->get();

        return view('dashboard.amenities', compact('amenities'));
    }

    public function amenitiesStore(Request $request)
    {
        $validated = $request->validate([
            'amenity_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_per_use' => ['required', 'numeric', 'min:0'],
        ]);

        Amenity::create($validated);

        return redirect()->route('dashboard.amenities')->with('success', 'Amenity added.');
    }

    public function amenitiesUpdate(Request $request, int $amenityId)
    {
        $amenity = Amenity::query()->where('amenity_id', $amenityId)->firstOrFail();

        $validated = $request->validate([
            'amenity_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_per_use' => ['required', 'numeric', 'min:0'],
        ]);

        $amenity->update($validated);

        return redirect()->route('dashboard.amenities')->with('success', 'Amenity updated.');
    }

    public function amenitiesDestroy(int $amenityId)
    {
        Amenity::query()->where('amenity_id', $amenityId)->delete();

        return redirect()->route('dashboard.amenities')->with('success', 'Amenity deleted.');
    }

    public function reservations()
    {
        $reservations = Reservation::query()
            ->with(['user', 'room'])
            ->orderByDesc('created_at')
            ->get();

        $users = User::query()->orderBy('full_name')->get();
        $rooms = Room::query()->orderBy('room_number')->get();

        return view('dashboard.reservations', compact('reservations', 'users', 'rooms'));
    }

    public function reservationsStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
            'room_id' => ['required', 'integer', 'exists:rooms,room_id'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'reservation_status' => ['required', 'string', 'max:255'],
        ]);

        $room = Room::query()->where('room_id', $validated['room_id'])->firstOrFail();
        $nights = max(1, now()->parse($validated['check_in_date'])->diffInDays(now()->parse($validated['check_out_date'])));
        $totalPrice = $nights * (float) $room->price_per_night;

        Reservation::create([
            'user_id' => $validated['user_id'],
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'reservation_status' => $validated['reservation_status'],
        ]);

        return redirect()->route('dashboard.reservations')->with('success', 'Reservation added.');
    }

    public function reservationsUpdate(Request $request, int $reservationId)
    {
        $reservation = Reservation::query()->where('reservation_id', $reservationId)->firstOrFail();

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
            'room_id' => ['required', 'integer', 'exists:rooms,room_id'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'reservation_status' => ['required', 'string', 'max:255'],
        ]);

        $room = Room::query()->where('room_id', $validated['room_id'])->firstOrFail();
        $nights = max(1, now()->parse($validated['check_in_date'])->diffInDays(now()->parse($validated['check_out_date'])));
        $totalPrice = $nights * (float) $room->price_per_night;

        $reservation->update([
            'user_id' => $validated['user_id'],
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'reservation_status' => $validated['reservation_status'],
        ]);

        return redirect()->route('dashboard.reservations')->with('success', 'Reservation updated.');
    }

    public function reservationsDestroy(int $reservationId)
    {
        Reservation::query()->where('reservation_id', $reservationId)->delete();

        return redirect()->route('dashboard.reservations')->with('success', 'Reservation deleted.');
    }

    public function approvals()
    {
        $pendingReservations = Reservation::query()
            ->with(['user', 'room'])
            ->where(function ($q) {
                $q->where('reservation_status', 'pending')
                    ->orWhere('reservation_status', 'Pending');
            })
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.approvals', compact('pendingReservations'));
    }

    public function approvalsApprove(int $reservationId)
    {
        Reservation::query()
            ->where('reservation_id', $reservationId)
            ->update(['reservation_status' => 'approved']);

        return redirect()->route('dashboard.approvals')->with('success', 'Reservation approved.');
    }

    public function approvalsReject(int $reservationId)
    {
        Reservation::query()
            ->where('reservation_id', $reservationId)
            ->update(['reservation_status' => 'rejected']);

        return redirect()->route('dashboard.approvals')->with('success', 'Reservation rejected.');
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();

        $reservations = Reservation::query()
            ->with('room')
            ->where('user_id', $user->user_id)
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.my-reservations', compact('reservations'));
    }

    public function reserve()
    {
        $availableRooms = Room::query()
            ->where('availability_status', 'available')
            ->orderBy('room_number')
            ->get();

        return view('dashboard.reserve', compact('availableRooms'));
    }
}
