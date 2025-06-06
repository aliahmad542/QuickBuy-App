<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products in {{ $getproduct->name }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: 'Inter', sans-serif; /* Modern font */
        }
        .store-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7); /* Gradient background */
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .store-header h1 {
            font-weight: 700; /* Bold heading */
            margin-bottom: 10px;
        }
        .store-header p {
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        .product-card {
            border: none;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: translateY(-5px); /* Slight lift effect on hover */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); /* Deeper shadow on hover */
        }
        .product-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .product-details {
            padding: 20px;
        }
        .product-details h5 {
            font-weight: 600;
            color: #333;
        }
        .product-details p {
            color: #6c757d; /* Gray text for description */
        }
        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0d6efd; /* Primary color for price */
        }
        .back-button {
            margin-top: 30px;
            text-align: center;
        }
        .delete-store-button {
            margin-top: 20px;
            text-align: center;
        }
    </style>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<!-- Store Header -->
<div class="store-header">
    <h1>{{ $getproduct->name }}</h1>
    <p>Explore our products below</p>
</div>

<!-- Delete Store Button -->
<div class="delete-store-button">
    <form id="delete-store-form" action="{{ route('store.delete', $getproduct->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
            <i class="fas fa-trash"></i> Delete Store
        </button>
    </form>
</div>
<!-- Products Section -->
<div class="container">
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $prod)
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <!-- Product Image -->
                        <div class="product-image">
                            <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}">
                        </div>
                        <!-- Product Details -->
                        <div class="product-details">
                            <h5>{{ $prod->name }}</h5>

                            <p>{{ $prod->description }}</p>
                            <p class="product-price">${{ $prod->price }}</p>
                            <a class="btn btn-danger" href="{{route('product.delete',[$prod->id,$getproduct])}}">delete</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning text-center">
            <strong>No products found in this store.</strong>
        </div>
    @endif

    <!-- Back Button -->
    <div class="back-button">
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Stores
        </a>
    </div>
        <div class="back-button">
            <a href="{{ route('product') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> add more
            </a>
        </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
