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

            @if($hotel->promotion)
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 16px; border-left: 4px solid #28a745;">
                    <strong>{{ $hotel->promotion->title }}</strong>
                    <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.9em; margin-left: 8px;">
                        {{ $hotel->promotion->discount_percent }}% OFF
                    </span>
                    <p style="margin: 4px 0 0 0;">{{ $hotel->promotion->description }}</p>
                </div>
            @endif

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
                    <form action="/book-hotel" method="POST" class="booking-form" style="margin-top: 20px;" onsubmit="return confirm('Are you sure you want to book this room?');">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        <input type="hidden" name="check_in" value="{{ request('check_in') }}">
                        <input type="hidden" name="check_out" value="{{ request('check_out') }}">

                        <h3 style="margin-bottom: 16px;">Available Room Types</h3>
                        <div class="room-grid">
                            @foreach($availableRooms as $type => $rooms)
                                @php 
                                    $firstRoom = $rooms->first();
                                    $originalPrice = $firstRoom->price;
                                    $discountedPrice = $hotel->promotion ? $hotel->promotion->applyDiscount($originalPrice) : $originalPrice;
                                @endphp
                                <div class="room-option">
                                    <input type="radio" name="room_type" value="{{ $type }}" 
                                           id="room_type_{{ $hotel->id }}_{{ $loop->index }}" 
                                           data-rooms="{{ $rooms->pluck('id')->implode(',') }}"
                                           data-capacity="{{ $firstRoom->capacity }}"
                                           required>
                                    <label for="room_type_{{ $hotel->id }}_{{ $loop->index }}" class="room-label">
                                        <div class="room-type">{{ $type }}</div>
                                        <div class="room-price">
                                            @if($hotel->promotion)
                                                <span style="text-decoration: line-through; color: #999; font-size: 0.9em;">${{ $originalPrice }}</span>
                                                <span style="color: #28a745; font-weight: bold;">${{ number_format($discountedPrice, 2) }}</span>
                                                / night
                                            @else
                                                ${{ $originalPrice }} / night
                                            @endif
                                        </div>
                                        <div class="room-available">{{ $rooms->count() }} available • Max {{ $firstRoom->capacity }} guests</div>
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

                        <div class="form-group" id="guests-section" style="display: none; margin-top: 20px;">
                            <label>Number of Guests</label>
                            <input type="number" name="guests" id="guests-input" min="1" required>
                            <p id="capacity-warning" style="color: #dc2626; font-size: 14px; margin-top: 8px; display: none;">
                                This room can only accommodate <span id="max-capacity"></span> guests.
                            </p>
                        </div>

                        <button type="submit" id="submit-button" disabled>Book Now</button>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const roomTypeRadios = document.querySelectorAll('input[name="room_type"]');
                            const roomNumberSection = document.getElementById('room-number-section');
                            const roomNumberSelect = document.getElementById('room-number-select');
                            const guestsSection = document.getElementById('guests-section');
                            const guestsInput = document.getElementById('guests-input');
                            const submitButton = document.getElementById('submit-button');
                            const capacityWarning = document.getElementById('capacity-warning');
                            const maxCapacitySpan = document.getElementById('max-capacity');

                            let currentCapacity = 0;
                        
                            roomTypeRadios.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    if (this.checked) {
                                        // Get available room IDs and capacity for this type
                                        const roomIds = this.dataset.rooms.split(',');
                                        currentCapacity = parseInt(this.dataset.capacity);

                                        // Clear and populate room dropdown
                                        roomNumberSelect.innerHTML = '<option value="">Choose a room number</option>';
                                        roomIds.forEach(roomId => {
                                            const option = document.createElement('option');
                                            option.value = roomId;
                                            option.textContent = `Room #${roomId}`;
                                            roomNumberSelect.appendChild(option);
                                        });
                                    
                                        // Show room number section
                                        roomNumberSection.style.display = 'block';
                                        roomNumberSelect.required = true;

                                        // Hide guests section until room is selected
                                        guestsSection.style.display = 'none';
                                        guestsInput.value = '';
                                        capacityWarning.style.display = 'none';

                                        // Disable submit
                                        submitButton.disabled = true;
                                    }
                                });
                            });
                        
                            roomNumberSelect.addEventListener('change', function() {
                                if (this.value) {
                                    // Show guests section
                                    guestsSection.style.display = 'block';
                                    guestsInput.max = currentCapacity;
                                    guestsInput.value = 1;
                                    maxCapacitySpan.textContent = currentCapacity;

                                    // Validate on change
                                    validateGuests();
                                } else {
                                    guestsSection.style.display = 'none';
                                    submitButton.disabled = true;
                                }
                            });

                            guestsInput.addEventListener('input', validateGuests);

                            function validateGuests() {
                                const guests = parseInt(guestsInput.value);
                                
                                if (guests > currentCapacity) {
                                    capacityWarning.style.display = 'block';
                                    submitButton.disabled = true;
                                } else if (guests >= 1) {
                                    capacityWarning.style.display = 'none';
                                    submitButton.disabled = false;
                                } else {
                                    submitButton.disabled = true;
                                }
                            }
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
        // Date validation for check-in/check-out
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInputs = document.querySelectorAll('input[name="check_in"]');
            
            checkInInputs.forEach(checkInInput => {
                const form = checkInInput.closest('form');
                const checkOutInput = form.querySelector('input[name="check_out"]');
                
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
        });
    </script>
</body>
</html>