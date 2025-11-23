<!DOCTYPE html>
<html>
<head><title>Book Hotel</title></head>
<body style="padding: 20px; font-family: sans-serif;">
    <a href="/visitor/dashboard">← Back</a>
    <h1>Book a Stay</h1>
    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color: red;">{{ session('error') }}</p> @endif

    @foreach($hotels as $hotel)
    <div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 8px; background: #fafafa;">
        <h2>{{ $hotel->name }}</h2>

        @php
            $roomTypes = $hotel->rooms->groupBy('type');
        @endphp

        @if($roomTypes->count() > 0)
            <form action="/book-hotel" method="POST" class="booking-form">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                <!-- Room Selection -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-bottom: 15px;">
                    @foreach($roomTypes as $type => $rooms)
                        @php $price = $rooms->first()->price; @endphp
                        <label style="background: white; border: 1px solid #ddd; padding: 10px; cursor: pointer; border-radius: 5px;">
                            <input type="radio" name="room_type" value="{{ $type }}" required>
                            <strong>{{ $type }}</strong><br>
                            <span style="color: green">${{ $price }}</span> / night<br>
                            <small style="color: grey">{{ $rooms->count() }} available</small>
                        </label>
                    @endforeach
                </div>

                <!-- Date Selection -->
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div>
                        <label>Check In:</label><br>
                        <!-- Give class 'check-in' for JS -->
                        <input type="date" name="check_in" class="check-in" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label>Check Out:</label><br>
                        <!-- Give class 'check-out' for JS -->
                        <input type="date" name="check_out" class="check-out" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label>Guests:</label><br>
                        <input type="number" name="guests" value="1" min="1" style="width: 60px;">
                    </div>
                </div>

                <button type="submit" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Book Now</button>
            </form>
        @else
            <p style="color: red;">No rooms available at this hotel currently.</p>
        @endif
    </div>
    @endforeach

    <!-- JAVASCRIPT FOR DATE VALIDATION -->
    <script>
        // Select all forms
        const forms = document.querySelectorAll('.booking-form');

        forms.forEach(form => {
            const checkInInput = form.querySelector('.check-in');
            const checkOutInput = form.querySelector('.check-out');

            checkInInput.addEventListener('change', function() {
                // Get the selected check-in date
                const checkInDate = this.value;

                if (checkInDate) {
                    // Update the minimum check-out date to be the day AFTER check-in
                    // Or same day if you allow day-use, but usually hotels are next day.
                    // Let's set it to the same day as minimum (user logic) or next day.

                    // Create a date object
                    let date = new Date(checkInDate);
                    // Add 1 day
                    date.setDate(date.getDate() + 1);

                    // Format to YYYY-MM-DD for the HTML input
                    const nextDay = date.toISOString().split('T')[0];

                    checkOutInput.min = nextDay;

                    // If the current checkout date is invalid (before new min), clear it
                    if (checkOutInput.value && checkOutInput.value < nextDay) {
                        checkOutInput.value = nextDay;
                    }
                }
            });
        });
    </script>
</body>
</html>
