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

            <!-- Step 1: Date Selection Form -->
            <form method="GET" action="/hotels" class="date-check-form">
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                <div class="date-section">
                    <div class="date-group">
                        <label>Check In</label>
                        <input type="date" name="check_in" required min="{{ date('Y-m-d') }}" 
                               value="{{ request('check_in') }}">
                    </div>
                    <div class="date-group">
                        <label>Check Out</label>
                        <input type="date" name="check_out" required min="{{ date('Y-m-d') }}" 
                               value="{{ request('check_out') }}">
                    </div>
                    <div class="date-group">
                        <label>Guests</label>
                        <input type="number" name="guests" value="{{ request('guests', 1) }}" min="1">
                    </div>
                </div>

                <button type="submit">Check Availability</button>
            </form>

            <!-- Step 2: Show Available Rooms (only if dates are selected and this is the selected hotel) -->
            @if(request('hotel_id') == $hotel->id && request('check_in') && request('check_out'))
                @php
                    $availableRooms = $hotel->getAvailableRoomsByType(
                        request('check_in'), 
                        request('check_out')
                    );
                @endphp
                
                @if($availableRooms->count() > 0)
                    <form action="/book-hotel" method="POST" class="booking-form" style="margin-top: 20px;">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        <input type="hidden" name="check_in" value="{{ request('check_in') }}">
                        <input type="hidden" name="check_out" value="{{ request('check_out') }}">
                        <input type="hidden" name="guests" value="{{ request('guests') }}">
                                
                        <h3 style="margin-bottom: 16px;">Available Room Types</h3>
                        <div class="room-grid">
                            @foreach($availableRooms as $type => $rooms)
                                @php $price = $rooms->first()->price; @endphp
                                <div class="room-option">
                                    <input type="radio" name="room_type" value="{{ $type }}" 
                                           id="room_type_{{ $hotel->id }}_{{ $loop->index }}" 
                                           data-rooms="{{ $rooms->pluck('id')->implode(',') }}"
                                           required>
                                    <label for="room_type_{{ $hotel->id }}_{{ $loop->index }}" class="room-label">
                                        <div class="room-type">{{ $type }}</div>
                                        <div class="room-price">${{ $price }} / night</div>
                                        <div class="room-available">{{ $rooms->count() }} available</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                                
                        <div class="form-group" id="room-number-section" style="display: none; margin-top: 20px;">
                            <label>Select Room Number</label>
                            <select name="room_id" id="room-number-select" required>
                                <option value="">Choose a room number</option>
                            </select>
                        </div>
                                
                        <button type="submit" id="submit-button" disabled>Book Now</button>
                    </form>
                                
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const roomTypeRadios = document.querySelectorAll('input[name="room_type"]');
                            const roomNumberSection = document.getElementById('room-number-section');
                            const roomNumberSelect = document.getElementById('room-number-select');
                            const submitButton = document.getElementById('submit-button');
                        
                            roomTypeRadios.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    if (this.checked) {
                                        // Get available room IDs for this type
                                        const roomIds = this.dataset.rooms.split(',');
                                        
                                        // Clear and populate dropdown
                                        roomNumberSelect.innerHTML = '<option value="">Choose a room number</option>';
                                        roomIds.forEach(roomId => {
                                            const option = document.createElement('option');
                                            option.value = roomId;
                                            option.textContent = `Room #${roomId}`;
                                            roomNumberSelect.appendChild(option);
                                        });
                                    
                                        // Show the dropdown section
                                        roomNumberSection.style.display = 'block';
                                        roomNumberSelect.required = true;
                                        
                                        // Disable submit until room is selected
                                        submitButton.disabled = true;
                                    }
                                });
                            });
                        
                            roomNumberSelect.addEventListener('change', function() {
                                // Enable submit button when room is selected
                                submitButton.disabled = !this.value;
                            });
                        });
                    </script>
                @else
                    <p class="no-rooms" style="margin-top: 16px;">
                        No rooms available for selected dates. Please try different dates.
                    </p>
                @endif
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