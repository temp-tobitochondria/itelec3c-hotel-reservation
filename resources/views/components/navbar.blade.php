<header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/55 backdrop-blur-xl">
    <div class="container-xl">
        <div class="h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-2xl bg-white text-slate-950 grid place-items-center font-bold tracking-tight">
                    A
                </div>
                <div class="leading-tight">
                    <div class="text-white font-semibold tracking-tight">Aurum Hotel</div>
                    <div class="text-[11px] text-white/60 -mt-0.5">World-class stays</div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm text-white/75">
    <a href="{{ url('/') }}#rooms" class="hover:text-white transition">Rooms</a>
    <a href="{{ url('/') }}#amenities" class="hover:text-white transition">Amenities</a>
    <a href="{{ url('/') }}#dining" class="hover:text-white transition">Dining</a>
    <a href="{{ url('/') }}#gallery" class="hover:text-white transition">Gallery</a>
    <a href="{{ url('/') }}#reviews" class="hover:text-white transition">Reviews</a>
    <a href="{{ route('about') }}" class="hover:text-white transition">
        About Us
    </a>
</nav>


            <div class="flex items-center gap-3">
                <a href="#rooms" class="hidden sm:inline-flex btn btn-soft bg-white/0 border-white/20 text-white hover:bg-white/10">
                    Explore
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    Login
                </a>
                <a href="#book" class="btn btn-primary">
                    Book Now
                </a>
            </div>
        </div>
    </div>
</header>
