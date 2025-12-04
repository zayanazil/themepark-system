<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f7fafc;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .back-link {
            display: inline-block;
            color: #2d3748;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        h1 {
            color: #1a202c;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        
        h2 {
            color: #1a202c;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .hotel-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .room-option {
            position: relative;
        }
        
        .room-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .room-label {
            display: block;
            background: white;
            border: 2px solid #e2e8f0;
            padding: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .room-option input[type="radio"]:checked + .room-label {
            border-color: #2d3748;
            background: #f7fafc;
        }
        
        .room-label:hover {
            border-color: #cbd5e0;
        }
        
        .room-type {
            color: #1a202c;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }
        
        .room-price {
            color: #2d3748;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 2px;
        }
        
        .room-available {
            color: #718096;
            font-size: 13px;
        }
        
        .date-section {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        
        .date-group {
            flex: 1;
            min-width: 150px;
        }
        
        label {
            display: block;
            color: #2d3748;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 15px;
            font-family: inherit;
        }
        
        input[type="number"] {
            width: 80px;
        }
        
        input[type="date"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #4a5568;
        }
        
        button {
            background: #2d3748;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: #1a202c;
        }
        
        .no-rooms {
            color: #c33;
            padding: 16px;
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 8px;
        }
    </style>
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