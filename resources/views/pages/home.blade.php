<x-layouts.app title="Aurum Hotel">
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-slate-950 text-white">
        {{-- glow accents --}}
        <div class="absolute -top-32 -right-32 h-96 w-96 glow rounded-full"></div>
        <div class="absolute -bottom-40 -left-40 h-[28rem] w-[28rem] glow rounded-full"></div>

        {{-- subtle grid --}}
        <div class="absolute inset-0 luxury-grid opacity-40"></div>

        {{-- hero gradient --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950/90 to-white"></div>

        <div class="relative container-xl pt-16 pb-10 sm:pt-20 sm:pb-14">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                <div class="lg:col-span-7">
                    <div class="pill">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Now accepting reservations • Best rates on direct booking
                    </div>

                    <h1 class="mt-5 text-4xl sm:text-6xl font-semibold tracking-tight leading-[1.05]">
                        A premium stay, <span class="text-white/80">designed</span><br class="hidden sm:block">
                        with quiet luxury.
                    </h1>

                    <p class="mt-6 max-w-xl text-white/75 text-base sm:text-lg leading-relaxed">
                        Boutique comfort, world-class service, and a booking experience that feels effortless.
                        Wake up to calm interiors, thoughtful amenities, and refined hospitality.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="#book" class="btn btn-primary">
                            Book a Room
                            <span aria-hidden="true">→</span>
                        </a>
                        <a href="#rooms" class="btn btn-ghost">
                            View Rooms
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-3 gap-6 max-w-md">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-2xl font-semibold">4.9</div>
                            <div class="text-xs text-white/60 mt-1">Guest rating</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-2xl font-semibold">12+</div>
                            <div class="text-xs text-white/60 mt-1">Luxury amenities</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-2xl font-semibold">24/7</div>
                            <div class="text-xs text-white/60 mt-1">Concierge</div>
                        </div>
                    </div>
                </div>

                {{-- Booking card --}}
                <div class="lg:col-span-5">
                    <div id="book" class="card card-hover border-white/10 bg-white/95 shadow-2xl shadow-slate-950/25">
                        <div class="p-6 sm:p-7">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Find your stay</h2>
                                    <p class="text-sm text-slate-600 mt-1">Instant availability • Secure booking</p>
                                </div>
                                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-600">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Open today
                                </div>
                            </div>

                            <form id="home-booking-form" method="GET" action="{{ route('dashboard.reserve') }}" class="mt-6 grid gap-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="text-xs text-slate-600">Check-in</label>
            <input
                type="date"
                name="check_in_date"
                required
                class="mt-1 w-full rounded-2xl border-slate-300
                       text-slate-900
                       focus:border-slate-900 focus:ring-slate-900"
            />
        </div>
        <div>
            <label class="text-xs text-slate-600">Check-out</label>
            <input
                type="date"
                name="check_out_date"
                required
                class="mt-1 w-full rounded-2xl border-slate-300
                       text-slate-900
                       focus:border-slate-900 focus:ring-slate-900"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="text-xs text-slate-600">Guests</label>
            <select
                name="guests"
                class="mt-1 w-full rounded-2xl border-slate-300
                       text-slate-900
                       focus:border-slate-900 focus:ring-slate-900">
                <option class="text-slate-900">1</option>
                <option class="text-slate-900">2</option>
                <option class="text-slate-900">3</option>
                <option class="text-slate-900">4</option>
            </select>
        </div>

        <div>
            <label class="text-xs text-slate-600">Room</label>
            <select
                name="room_type"
                class="mt-1 w-full rounded-2xl border-slate-300
                       text-slate-900
                       focus:border-slate-900 focus:ring-slate-900">
                <option class="text-slate-900" value="Any">Any</option>
                <option class="text-slate-900" value="Standard Room">Standard Room</option>
                <option class="text-slate-900" value="Double Room">Double Room</option>
                <option class="text-slate-900" value="Family Room">Family Room</option>
                <option class="text-slate-900" value="Deluxe Room">Deluxe Room</option>
                <option class="text-slate-900" value="Aurum Suite">Aurum Suite</option>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-full rounded-2xl py-3">
        Check Availability
    </button>

    <div class="flex items-center justify-between text-xs text-slate-500">
        <span>No hidden fees</span>
        <span>Free cancellation (selected rates)</span>
    </div>
</form>

                            <script>
                                window.addEventListener('DOMContentLoaded', () => {
                                    const form = document.getElementById('home-booking-form');
                                    if (!form) return;

                                    const checkIn = form.querySelector('input[name="check_in_date"]');
                                    const checkOut = form.querySelector('input[name="check_out_date"]');
                                    if (!checkIn || !checkOut) return;

                                    const pad2 = (n) => String(n).padStart(2, '0');
                                    const toYmd = (d) => `${d.getFullYear()}-${pad2(d.getMonth() + 1)}-${pad2(d.getDate())}`;

                                    const today = new Date();
                                    today.setHours(0, 0, 0, 0);
                                    const todayStr = toYmd(today);

                                    checkIn.min = todayStr;
                                    checkOut.min = todayStr;

                                    const syncCheckoutMin = () => {
                                        if (!checkIn.value) {
                                            checkOut.min = todayStr;
                                            return;
                                        }

                                        const inDate = new Date(`${checkIn.value}T00:00:00`);
                                        if (Number.isNaN(inDate.getTime())) {
                                            checkOut.min = todayStr;
                                            return;
                                        }

                                        const minOut = new Date(inDate);
                                        minOut.setDate(minOut.getDate() + 1);
                                        const minOutStr = toYmd(minOut);
                                        checkOut.min = minOutStr;

                                        if (checkOut.value && checkOut.value < minOutStr) {
                                            checkOut.value = '';
                                        }
                                    };

                                    checkIn.addEventListener('change', syncCheckoutMin);
                                    syncCheckoutMin();
                                });
                            </script>

                            @guest
                                <script>
                                    window.addEventListener('DOMContentLoaded', () => {
                                        const form = document.getElementById('home-booking-form');
                                        if (!form) return;

                                        form.addEventListener('submit', async (e) => {
                                            e.preventDefault();

                                            const ensure = window.ensureSwal
                                                ? window.ensureSwal
                                                : () => Promise.resolve(window.Swal);

                                            const Swal = await ensure();
                                            if (!Swal) {
                                                window.location.href = @json(route('login'));
                                                return;
                                            }

                                            const result = await Swal.fire({
                                                icon: 'info',
                                                title: 'Sign in required',
                                                text: 'Please sign in to book a reservation.',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sign in',
                                                cancelButtonText: 'Cancel',
                                                reverseButtons: true,
                                            });

                                            if (result.isConfirmed) {
                                                window.location.href = @json(route('login'));
                                            }
                                        });
                                    });
                                </script>
                            @endguest

                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-white/80">
                            <div class="font-medium text-white">Direct Booking Perk</div>
                            <div class="mt-1 text-white/60">Complimentary welcome drink</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-white/80">
                            <div class="font-medium text-white">Best Rate Guarantee</div>
                            <div class="mt-1 text-white/60">Guaranteed lowest price</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- transition to white --}}
        <div class="relative h-16 bg-gradient-to-b from-white/0 to-white"></div>
    </section>

    {{-- TRUST STRIP --}}
<section class="bg-white">
    <div class="container-xl py-10">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm text-slate-600">

            {{-- Airport Transfer --}}
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 grid place-items-center">
                    <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="1.8"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 6h3m-1.5 12V6m-6 6h12M4 12h2m12 0h2" />
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-900">Airport Transfer</div>
                    <div class="text-xs text-slate-600">On request</div>
                </div>
            </div>

            {{-- Fast Wi-Fi --}}
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 grid place-items-center">
                    <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="1.8"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.53 16.11a6 6 0 016.94 0M5.1 13.7a10 10 0 0113.8 0M2.3 10.9a14 14 0 0119.4 0" />
                        <circle cx="12" cy="18" r="1" />
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-900">Fast Wi-Fi</div>
                    <div class="text-xs text-slate-600">Work ready</div>
                </div>
            </div>

            {{-- Breakfast --}}
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 grid place-items-center">
                    <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="1.8"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M6 6v10a4 4 0 004 4h4a4 4 0 004-4V6" />
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-900">Breakfast</div>
                    <div class="text-xs text-slate-600">Daily</div>
                </div>
            </div>

            {{-- Concierge --}}
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 grid place-items-center">
                    <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="1.8"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6a3 3 0 100 6 3 3 0 000-6zM4 20a8 8 0 0116 0" />
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-900">Concierge</div>
                    <div class="text-xs text-slate-600">24/7</div>
                </div>
            </div>

        </div>
    </div>
</section>

        </div>
    </section>

   {{-- ROOMS --}}
<section id="rooms" class="py-16 bg-white">
    <div class="container-xl">
        <div class="flex items-end justify-between gap-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Rooms & Suites</h2>
                <p class="text-slate-600 mt-2 max-w-2xl">
                    Calm, spacious, and intentionally designed—soft lighting, premium linens, and refined materials.
                </p>
            </div>
            <a href="#book" class="hidden sm:inline-flex btn btn-soft">
                Book from here
            </a>
        </div>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([
                [
                    'name'=>'Standard Room',
                    'tag'=>'Best value',
                    'desc'=>'Minimalist comfort for solo stays and short trips.',
                    'price'=>'₱2,499/night',
                    'image'=>'standard-room.png',
                ],
                [
                    'name'=>'Double Room',
                    'tag'=>'Comfort',
                    'desc'=>'Ideal for couples, featuring a spacious double bed and cozy interiors.',
                    'price'=>'₱2,999/night',
                    'image'=>'double-room.png',
                ],
                [
                    'name'=>'Deluxe Room',
                    'tag'=>'Most booked',
                    'desc'=>'More space, better views, and a premium feel.',
                    'price'=>'₱3,699/night',
                    'image'=>'deluxe-room.png',
                ],
                [
                    'name'=>'Family Room',
                    'tag'=>'Family favorite',
                    'desc'=>'Designed for families, offering extra space and multiple sleeping options.',
                    'price'=>'₱4,999/night',
                    'image'=>'family-room.png',
                ],
                [
                    'name'=>'Aurum Suite',
                    'tag'=>'Signature',
                    'desc'=>'Lounge area + spa-style bath for long stays.',
                    'price'=>'₱6,999/night',
                    'image'=>'aurum-suite.png',
                ],
            ] as $room)
                <div class="card card-hover overflow-hidden">
                    <div class="h-52 border-b border-slate-200 relative overflow-hidden bg-slate-100">
                        <img
                            src="{{ asset('images/gallery/' . $room['image']) }}"
                            alt="{{ $room['name'] }}"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute left-4 top-4 rounded-full bg-white/90 border border-slate-200 px-3 py-1 text-xs text-slate-700">
                            {{ $room['tag'] }}
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-lg font-semibold">{{ $room['name'] }}</div>
                                <div class="text-sm text-slate-600 mt-1">{{ $room['desc'] }}</div>
                            </div>
                            <div class="text-sm font-semibold text-slate-900 whitespace-nowrap">
                                {{ $room['price'] }}
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-600">
                            <span class="px-3 py-1 rounded-full bg-slate-100">King/Queen</span>
                            <span class="px-3 py-1 rounded-full bg-slate-100">Rain shower</span>
                            <span class="px-3 py-1 rounded-full bg-slate-100">Workspace</span>
                            <span class="px-3 py-1 rounded-full bg-slate-100">Wi-Fi</span>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <a href="#book" class="btn btn-primary flex-1">Reserve</a>
                            <a href="#" class="btn btn-soft">Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


   {{-- AMENITIES --}}
<section id="amenities" class="py-16 bg-slate-50">
    <div class="container-xl">
        <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Amenities</h2>
        <p class="text-slate-600 mt-2 max-w-2xl">
            Premium essentials, thoughtfully curated—from wellness to business-ready comfort.
        </p>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ([
                [
                    't'=>'Infinity Pool',
                    'd'=>'A calm space to unwind.',
                    'icon'=>'pool'
                ],
                [
                    't'=>'Spa & Wellness',
                    'd'=>'Massage and sauna options.',
                    'icon'=>'spa'
                ],
                [
                    't'=>'Fitness Studio',
                    'd'=>'Modern equipment and towels.',
                    'icon'=>'fitness'
                ],
                [
                    't'=>'High-Speed Wi-Fi',
                    'd'=>'Work & streaming ready.',
                    'icon'=>'wifi'
                ],
                [
                    't'=>'Airport Transfer',
                    'd'=>'Convenient pickup on request.',
                    'icon'=>'transfer'
                ],
                [
                    't'=>'Laundry Service',
                    'd'=>'Same-day options available.',
                    'icon'=>'laundry'
                ],
                [
                    't'=>'Secure Parking',
                    'd'=>'Safe and accessible.',
                    'icon'=>'parking'
                ],
                [
                    't'=>'24/7 Concierge',
                    'd'=>'We respond quickly.',
                    'icon'=>'concierge'
                ],
            ] as $a)
                <div class="card card-hover p-6 bg-white">
                    <div class="h-11 w-11 rounded-2xl bg-slate-900 grid place-items-center">
                        @switch($a['icon'])

                            @case('pool')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 18c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2" />
                                </svg>
                                @break

                            @case('spa')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 3v6m0 0a3 3 0 100 6 3 3 0 000-6z" />
                                </svg>
                                @break

                            @case('fitness')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6 8v8m12-8v8M3 10h18M3 14h18" />
                                </svg>
                                @break

                            @case('wifi')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8.53 16.11a6 6 0 016.94 0M5.1 13.7a10 10 0 0113.8 0M2.3 10.9a14 14 0 0119.4 0" />
                                    <circle cx="12" cy="18" r="1" />
                                </svg>
                                @break

                            @case('transfer')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 7h13l3 4v6H3zM7 17a1 1 0 100-2 1 1 0 000 2zM17 17a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                                @break

                            @case('laundry')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6 3h12v18H6zM9 7h.01M12 12a3 3 0 100 6 3 3 0 000-6z" />
                                </svg>
                                @break

                            @case('parking')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 4h6a4 4 0 010 8H7zM7 12v8" />
                                </svg>
                                @break

                            @case('concierge')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6a3 3 0 100 6 3 3 0 000-6zM4 20a8 8 0 0116 0" />
                                </svg>
                                @break

                        @endswitch
                    </div>

                    <div class="mt-4 font-semibold text-slate-900">
                        {{ $a['t'] }}
                    </div>
                    <div class="mt-1 text-sm text-slate-600">
                        {{ $a['d'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


   {{-- DINING --}}
<section id="dining" class="py-16 bg-white">
    <div class="container-xl">
        <div class="grid lg:grid-cols-12 gap-8 items-center">
            {{-- Image --}}
            <div class="lg:col-span-6">
                <div class="card overflow-hidden relative rounded-3xl border border-slate-200">
                    <img
                        src="{{ asset('images/gallery/buffet.jpg') }}"
                        alt="Aurum Hotel Dining"
                        class="h-80 w-full object-cover transition-transform duration-500 hover:scale-105"
                    />

                    {{-- subtle overlay for luxury look --}}
                    <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
                </div>
            </div>

            {{-- Text --}}
            <div class="lg:col-span-6">
                <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Dining, refined</h2>
                <p class="text-slate-600 mt-3 leading-relaxed">
                    Seasonal menus, crafted cocktails, and a relaxed lounge atmosphere. Whether it’s breakfast,
                    business lunch, or late-night comfort—everything is designed for quality and ease.
                </p>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-3xl border border-slate-200 p-5 bg-white">
                        <div class="font-semibold">Signature Breakfast</div>
                        <div class="text-sm text-slate-600 mt-1">Fresh, daily options</div>
                    </div>
                    <div class="rounded-3xl border border-slate-200 p-5 bg-white">
                        <div class="font-semibold">Lobby Bar</div>
                        <div class="text-sm text-slate-600 mt-1">Classic &amp; modern cocktails</div>
                    </div>
                </div>

                <div class="mt-7">
                    <a href="#book" class="btn btn-primary">Reserve a stay</a>
                </div>
            </div>
        </div>
    </div>
</section>


    {{-- REVIEWS --}}
    <section id="reviews" class="py-16 bg-slate-50">
        <div class="container-xl">
            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Guest reviews</h2>
            <p class="text-slate-600 mt-2 max-w-2xl">Consistently praised for cleanliness, comfort, and service.</p>

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach ([
                    ['n'=>'Maria S.','m'=>'Check-in was smooth, rooms were spotless, and the vibe feels premium without being loud.'],
                    ['n'=>'Ken D.','m'=>'The minimalist interiors are beautiful. Fast Wi-Fi, great lighting, and super quiet at night.'],
                    ['n'=>'Anne P.','m'=>'Suite was stunning. Service was quick and thoughtful. Would book again immediately.'],
                ] as $r)
                    <div class="card card-hover p-6">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">{{ $r['n'] }}</div>
                            <div class="text-xs text-slate-500">★★★★★</div>
                        </div>
                        <p class="text-sm text-slate-700 mt-3 leading-relaxed">{{ $r['m'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

  <section id="gallery" class="py-16 bg-white">
    <div class="container-xl">
        <h2 class="text-3xl font-semibold">Gallery</h2>
        <p class="text-slate-600 mt-2">
            A glimpse into the Aurum Hotel experience.
        </p>

        <div class="mt-10 grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ([
                'download.jpg',
                'dining1.jpg',
                'Park pool.jpg',
                'Sanghai.jpg',
                'evels1.jpg',
                'room1.jpg',
            ] as $image)
                <div class="group relative overflow-hidden rounded-3xl">
                    <img
                        src="{{ asset('images/gallery/' . $image) }}"
                        alt="Hotel gallery image"
                        class="h-full w-full object-cover aspect-[4/3] transition-transform duration-500 group-hover:scale-105"
                    />

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>




    {{-- CTA --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-slate-900 text-white p-8 sm:p-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                <div>
                    <h3 class="text-2xl font-semibold tracking-tight">Ready to book your stay?</h3>
                    <p class="text-white/80 mt-2 max-w-xl">
                        Choose your room, confirm dates, and you’re done. Clean checkout flow coming next.
                    </p>
                </div>
                <a href="#book" class="inline-flex justify-center px-5 py-3 rounded-2xl bg-white text-slate-900 font-medium hover:bg-slate-100">
                    Book Now
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>


