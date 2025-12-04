<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="container">
        <a href="/visitor/dashboard" class="back-link">← Back</a>
        
        <h1>Book a Stay</h1>
        
        @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
        @endif

        @foreach($hotels as $hotel)
        <div class="hotel-card">
            <h2>{{ $hotel->name }}</h2>
    
            @php
                $roomTypes = $hotel->rooms->groupBy('type');
            @endphp

            @if($roomTypes->count() > 0)
                <form action="/book-hotel" method="POST" class="booking-form">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                    <div class="room-grid">
                        @foreach($roomTypes as $type => $rooms)
                            @php $price = $rooms->first()->price; @endphp
                            <div class="room-option">
                                <input type="radio" name="room_type" value="{{ $type }}" id="room_{{ $hotel->id }}_{{ $loop->index }}" required>
                                <label for="room_{{ $hotel->id }}_{{ $loop->index }}" class="room-label">
                                    <div class="room-type">{{ $type }}</div>
                                    <div class="room-price">${{ $price }} / night</div>
                                    <div class="room-available">{{ $rooms->count() }} available</div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="date-section">
                        <div class="date-group">
                            <label>Check In</label>
                            <input type="date" name="check_in" class="check-in" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="date-group">
                            <label>Check Out</label>
                            <input type="date" name="check_out" class="check-out" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="date-group">
                            <label>Guests</label>
                            <input type="number" name="guests" value="1" min="1">
                        </div>
                    </div>

                    <button type="submit">Book Now</button>
                </form>
            @else
                <p class="no-rooms">No rooms available at this hotel currently.</p>
            @endif
        </div>
        @endforeach
    </div>

    <script>
        const forms = document.querySelectorAll('.booking-form');

        forms.forEach(form => {
            const checkInInput = form.querySelector('.check-in');
            const checkOutInput = form.querySelector('.check-out');

            checkInInput.addEventListener('change', function() {
                const checkInDate = this.value;

                if (checkInDate) {
                    let date = new Date(checkInDate);
                    date.setDate(date.getDate() + 1);

                    const nextDay = date.toISOString().split('T')[0];

                    checkOutInput.min = nextDay;

                    if (checkOutInput.value && checkOutInput.value < nextDay) {
                        checkOutInput.value = nextDay;
                    }
                }
            });
        });
    </script>
</body>
</html>