<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function formatDateOnly(?string $value): string
    {
        if (!$value) {
            return '—';
        }

        return Carbon::parse($value)->toDateString();
    }

    private function logAction(Request $request, string $action): void
    {
        $actor = $request->user();
        if (!$actor) {
            return;
        }

        UserLog::record($actor->user_id, $action);
    }

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
            ->paginate(10)
            ->withQueryString();

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

        $newUser = User::create([
            'full_name' => $validated['full_name'],
            'role' => $validated['role'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $this->logAction($request, "Created user '{$newUser->username}' (role: {$newUser->role})");

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

        $this->logAction($request, "Updated user '{$user->username}' (ID: {$user->user_id})");

        return redirect()->route('dashboard.users')->with('success', 'User updated.');
    }

    public function usersDestroy(Request $request, int $userId)
    {
        $user = User::query()->where('user_id', $userId)->first();
        User::query()->where('user_id', $userId)->delete();

        if ($user) {
            $this->logAction($request, "Deleted user '{$user->username}' (ID: {$user->user_id})");
        }

        return redirect()->route('dashboard.users')->with('success', 'User deleted.');
    }

    public function logs()
    {
        $logs = UserLog::query()
            ->with('user')
            ->orderByDesc('date_time')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.logs', compact('logs'));
    }

    public function rooms()
    {
        $rooms = Room::query()
            ->orderBy('room_number')
            ->paginate(10)
            ->withQueryString();

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

        $room = Room::create($validated);

        $this->logAction($request, "Created room {$room->room_number} ({$room->room_type})");

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

        $this->logAction($request, "Updated room {$room->room_number} (ID: {$room->room_id})");

        return redirect()->route('dashboard.rooms')->with('success', 'Room updated.');
    }

    public function roomsDestroy(Request $request, int $roomId)
    {
        $room = Room::query()->where('room_id', $roomId)->first();
        Room::query()->where('room_id', $roomId)->delete();

        if ($room) {
            $this->logAction($request, "Deleted room {$room->room_number} (ID: {$room->room_id})");
        }

        return redirect()->route('dashboard.rooms')->with('success', 'Room deleted.');
    }

    public function amenities()
    {
        $amenities = Amenity::query()
            ->orderBy('amenity_name')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.amenities', compact('amenities'));
    }

    public function amenitiesStore(Request $request)
    {
        $validated = $request->validate([
            'amenity_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_per_use' => ['required', 'numeric', 'min:0'],
        ]);

        $amenity = Amenity::create($validated);

        $this->logAction($request, "Created amenity '{$amenity->amenity_name}'");

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

        $this->logAction($request, "Updated amenity '{$amenity->amenity_name}' (ID: {$amenity->amenity_id})");

        return redirect()->route('dashboard.amenities')->with('success', 'Amenity updated.');
    }

    public function amenitiesDestroy(Request $request, int $amenityId)
    {
        $amenity = Amenity::query()->where('amenity_id', $amenityId)->first();
        Amenity::query()->where('amenity_id', $amenityId)->delete();

        if ($amenity) {
            $this->logAction($request, "Deleted amenity '{$amenity->amenity_name}' (ID: {$amenity->amenity_id})");
        }

        return redirect()->route('dashboard.amenities')->with('success', 'Amenity deleted.');
    }

    public function reservations()
    {
        $reservations = Reservation::query()
            ->with(['user', 'room', 'amenities'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

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

        $reservation = Reservation::create([
            'user_id' => $validated['user_id'],
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'reservation_status' => $validated['reservation_status'],
        ]);

        $reservationUser = User::query()->where('user_id', $reservation->user_id)->first();
        $username = $reservationUser?->username ?? (string) $reservation->user_id;
        $checkIn = $this->formatDateOnly($reservation->check_in_date);
        $checkOut = $this->formatDateOnly($reservation->check_out_date);

        $this->logAction($request, "Created reservation #{$reservation->reservation_id} for {$username} (Room {$room->room_number}, {$checkIn} to {$checkOut})");

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

        $reservationUser = User::query()->where('user_id', $reservation->user_id)->first();
        $username = $reservationUser?->username ?? (string) $reservation->user_id;
        $checkIn = $this->formatDateOnly($reservation->check_in_date);
        $checkOut = $this->formatDateOnly($reservation->check_out_date);

        $this->logAction($request, "Updated reservation #{$reservation->reservation_id} for {$username} (Room {$room->room_number}, {$checkIn} to {$checkOut}, status: {$reservation->reservation_status})");

        return redirect()->route('dashboard.reservations')->with('success', 'Reservation updated.');
    }

    public function reservationsDestroy(Request $request, int $reservationId)
    {
        $reservation = Reservation::query()->with(['user', 'room'])->where('reservation_id', $reservationId)->first();
        Reservation::query()->where('reservation_id', $reservationId)->delete();

        if ($reservation) {
            $username = $reservation->user?->username ?? (string) $reservation->user_id;
            $roomNumber = $reservation->room?->room_number ?? (string) $reservation->room_id;
            $checkIn = $this->formatDateOnly($reservation->check_in_date);
            $checkOut = $this->formatDateOnly($reservation->check_out_date);

            $this->logAction($request, "Deleted reservation #{$reservation->reservation_id} for {$username} (Room {$roomNumber}, {$checkIn} to {$checkOut})");
        }

        return redirect()->route('dashboard.reservations')->with('success', 'Reservation deleted.');
    }

    public function approvals()
    {
        $pendingReservations = Reservation::query()
            ->with(['user', 'room', 'amenities'])
            ->where(function ($q) {
                $q->where('reservation_status', 'pending')
                    ->orWhere('reservation_status', 'Pending');
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.approvals', compact('pendingReservations'));
    }

    public function approvalsApprove(Request $request, int $reservationId)
    {
        $reservation = Reservation::query()
            ->with(['user', 'room'])
            ->where('reservation_id', $reservationId)
            ->firstOrFail();

        $reservation->update(['reservation_status' => 'approved']);

        Room::query()
            ->where('room_id', $reservation->room_id)
            ->update(['availability_status' => 'unavailable']);

        $username = $reservation->user?->username ?? (string) $reservation->user_id;
        $roomNumber = $reservation->room?->room_number ?? (string) $reservation->room_id;
        $checkIn = $this->formatDateOnly($reservation->check_in_date);
        $checkOut = $this->formatDateOnly($reservation->check_out_date);

        $this->logAction($request, "Approved reservation #{$reservation->reservation_id} for {$username} (Room {$roomNumber}, {$checkIn} to {$checkOut})");

        return redirect()->route('dashboard.approvals')->with('success', 'Reservation approved.');
    }

    public function approvalsReject(Request $request, int $reservationId)
    {
        $reservation = Reservation::query()->with(['user', 'room'])->where('reservation_id', $reservationId)->first();

        Reservation::query()
            ->where('reservation_id', $reservationId)
            ->update(['reservation_status' => 'rejected']);

        if ($reservation) {
            $username = $reservation->user?->username ?? (string) $reservation->user_id;
            $roomNumber = $reservation->room?->room_number ?? (string) $reservation->room_id;
            $checkIn = $this->formatDateOnly($reservation->check_in_date);
            $checkOut = $this->formatDateOnly($reservation->check_out_date);

            $this->logAction($request, "Rejected reservation #{$reservation->reservation_id} for {$username} (Room {$roomNumber}, {$checkIn} to {$checkOut})");
        }

        return redirect()->route('dashboard.approvals')->with('success', 'Reservation rejected.');
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();

        $reservations = Reservation::query()
            ->with(['room', 'amenities'])
            ->where('user_id', $user->user_id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.my-reservations', compact('reservations'));
    }

    public function reserve(Request $request)
    {
        return $this->reserveWithFilters($request);
    }

    private function reserveWithFilters(Request $request)
    {
        $validated = $request->validate([
            'check_in_date' => ['nullable', 'date', 'after_or_equal:today'],
            'check_out_date' => ['nullable', 'date', 'after:check_in_date'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'room_type' => ['nullable', 'string', 'max:255'],
        ]);

        $checkIn = $validated['check_in_date'] ?? null;
        $checkOut = $validated['check_out_date'] ?? null;
        $guests = $validated['guests'] ?? null;
        $roomType = $validated['room_type'] ?? null;

        $availableRooms = Room::query()
            ->where('availability_status', 'available')
            ->when($guests, fn ($q) => $q->where('capacity', '>=', $guests))
            ->when($roomType && strtolower($roomType) !== 'any', fn ($q) => $q->where('room_type', $roomType))
            ->when($checkIn && $checkOut, function ($q) use ($checkIn, $checkOut) {
                $q->whereDoesntHave('reservations', function ($rq) use ($checkIn, $checkOut) {
                    $rq->whereNotIn('reservation_status', ['rejected', 'Rejected', 'cancelled', 'Cancelled'])
                        ->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn);
                });
            })
            ->orderBy('room_number')
            ->get();

        $amenities = Amenity::query()
            ->orderBy('amenity_name')
            ->get();

        return view('dashboard.reserve', [
            'availableRooms' => $availableRooms,
            'amenities' => $amenities,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
            'roomType' => $roomType,
        ]);
    }

    public function reserveStore(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,room_id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'amenity_ids' => ['nullable', 'array'],
            'amenity_ids.*' => ['integer', 'exists:amenities,amenity_id'],
        ]);

        $room = Room::query()->where('room_id', $validated['room_id'])->firstOrFail();
        if ($room->availability_status !== 'available') {
            return redirect()->route('dashboard.reserve', $request->only(['check_in_date', 'check_out_date', 'guests', 'room_type']))
                ->with('error', 'Selected room is not available.');
        }

        $hasConflict = Reservation::query()
            ->where('room_id', $room->room_id)
            ->whereNotIn('reservation_status', ['rejected', 'Rejected', 'cancelled', 'Cancelled'])
            ->where('check_in_date', '<', $validated['check_out_date'])
            ->where('check_out_date', '>', $validated['check_in_date'])
            ->exists();

        if ($hasConflict) {
            return redirect()->route('dashboard.reserve', $request->only(['check_in_date', 'check_out_date', 'guests', 'room_type']))
                ->with('error', 'That room is already reserved for the selected dates.');
        }

        $nights = max(1, now()->parse($validated['check_in_date'])->diffInDays(now()->parse($validated['check_out_date'])));
        $baseTotal = $nights * (float) $room->price_per_night;

        $amenityIds = $validated['amenity_ids'] ?? [];
        $amenities = Amenity::query()
            ->whereIn('amenity_id', $amenityIds)
            ->get(['amenity_id', 'price_per_use']);

        $amenitiesTotal = (float) $amenities->sum(fn ($a) => (float) $a->price_per_use);
        $totalPrice = $baseTotal + $amenitiesTotal;

        $reservation = Reservation::create([
            'user_id' => $user->user_id,
            'room_id' => $room->room_id,
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'reservation_status' => 'pending',
        ]);

        if ($amenities->isNotEmpty()) {
            foreach ($amenities as $amenity) {
                $reservation->amenities()->attach($amenity->amenity_id, [
                    'price_per_use' => $amenity->price_per_use,
                ]);
            }
        }

        $checkIn = $this->formatDateOnly($reservation->check_in_date);
        $checkOut = $this->formatDateOnly($reservation->check_out_date);

        $username = $user->username ?? ($user->full_name ?? 'User');
        $this->logAction($request, "{$username} created reservation #{$reservation->reservation_id} (Room {$room->room_number}, {$checkIn} to {$checkOut})");

        if (($user->role ?? null) === 'Customer') {
            return redirect()->route('dashboard.my-reservations')->with('success', 'Reservation created and is pending approval.');
        }

        return redirect()->route('dashboard.reservations')->with('success', 'Reservation created and is pending approval.');
    }
}
