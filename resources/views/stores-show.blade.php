
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stores and Products</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: 'Inter', sans-serif; /* Modern font */
            color: #333; /* Dark gray text for better readability */
        }

        h1 {
            font-weight: 700; /* Bold heading */
            color: #0d6efd; /* Primary color */
            margin-bottom: 2rem; /* More spacing */
        }

        /* Store Card Styles */
        .store-card {
            border: none; /* Remove default border */
            border-radius: 12px; /* Rounded corners */
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Softer shadow */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden; /* Ensure content stays within the card */
            margin-bottom: 20px;
        }

        .store-card:hover {
            transform: translateY(-5px); /* Slight lift effect on hover */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); /* Deeper shadow on hover */
        }

        .store-card-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7); /* Gradient background */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 1.25rem; /* Larger font size */
            font-weight: 600; /* Semi-bold text */
        }

        .store-image {
            text-align: center;
            padding: 20px;
            position: relative; /* Needed for the pointer */
        }

        .store-image img {
            width: 100%; /* Make the image responsive */
            height: 200px; /* Fixed height for uniformity */
            object-fit: cover; /* Ensure the image fills the space without distortion */
            border-radius: 10px;
        }

        .store-image p {
            color: #6c757d; /* Gray text for placeholder */
            font-style: italic;
        }

        /* Pointer Styles */
        .pointer {
            position: absolute; /* Position the pointer absolutely */
            bottom: -10px; /* Position it below the image */
            left: 50%; /* Center it horizontally */
            transform: translateX(-50%); /* Center it precisely */
            font-size: 24px; /* Size of the pointer */
            color: #0d6efd; /* Color of the pointer */
            cursor: pointer; /* Change cursor to pointer */
            transition: color 0.3s ease; /* Smooth color transition */
        }

        .pointer:hover {
            color: #0b5ed7; /* Darker color on hover */
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7, #0a58ca);
            transform: translateY(-2px); /* Slight lift effect */
        }

        /* Alert Styles */
        .alert-warning {
            background-color: #fff3cd; /* Light yellow background */
            border-color: #ffeeba; /* Yellow border */
            color: #856404; /* Dark yellow text */
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        /* Responsive Layout */
        .store-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the cards */
            gap: 20px; /* Space between cards */
            padding: 20px;
        }

        .store-card {
            flex: 1 1 calc(25% - 40px); /* 4 cards per row on larger screens */
            max-width: 300px; /* Maximum width for each card */
        }

        @media (max-width: 1200px) {
            .store-card {
                flex: 1 1 calc(33.33% - 40px); /* 3 cards per row on medium screens */
            }
        }

        @media (max-width: 768px) {
            .store-card {
                flex: 1 1 calc(50% - 40px); /* 2 cards per row on tablets */
            }
        }

        @media (max-width: 576px) {
            .store-card {
                flex: 1 1 100%; /* 1 card per row on mobile */
            }
        }
    </style>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Welcome to Our Website</h1>

    @if($stores && $stores->count())
        <div class="store-container">
            @foreach($stores as $store)
                <div class="store-card">
                    <!-- Store Header -->
                    <div class="store-card-header">
                        <h2>{{ $store->name }}</h2>
                    </div>

                    <!-- Store Image -->
                    <div class="store-image">
                        @if($store->image)
                            <img src="{{ asset($store->image) }}" alt="{{ $store->name }}">
                            {{--                            <p>Image URL: {{ asset('storage/' . $store->image) }}</p>--}}
                            {{--                            <img src="{{ asset('storage/' . $store->image) }}" alt="{{ $store->name }}" style="width: 200px; height: auto;">--}}

                        @else

                            <p class="text-muted">No image available for this store.</p>
                        @endif
                        <!-- Pointer Below the Image -->
                        <a  class="pointer" href="{{ route('store.details', $store->id) }}">
                            <i class="fas fa-arrow-down"></i> <!-- Font Awesome arrow icon -->
                        </a>
                    </div>

                    <!-- Products Section -->
                    <div class="card-body">
                        <h5 class="text-secondary"></h5>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Create Store Button -->
        <div class="text-center mt-4">
            <a href="{{ route('stores.show') }}" class="btn btn-primary">Create Store</a>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('orders.get') }}" class="btn btn-primary">show orders</a>
        </div>

    @else
        <div class="alert alert-warning">
            <strong>No stores found.</strong> Create your first store to get started!
        </div>
    @endif
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
