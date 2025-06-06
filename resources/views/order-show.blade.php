<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders with Products and Users</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Orders with Products and Users</h1>

    @if($orders->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Products</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'No User Found' }}</td>
                    <td>
                        @if($order->product->count() > 0)
                            <ul>
                                @foreach($order->product as $product)
                                    <li>{{ $product->name ?? 'No Product Found' }}</li>
                                @endforeach
                            </ul>
                        @else
                            No Products Found
                        @endif
                    </td>
                    <td>{{ $order->quantity ?? 0 }}</td>
                    <td>
                        @if($order->is_pending == 1)
                            <a href="{{route('accept',$order->id)}}" class="btn btn-success btn-sm">Accept</a>
                        @else
                            <a href="{{route('refuse',$order->id)}}" class="btn btn-primary btn-sm">Complete</a>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning text-center">
            <strong>No orders found.</strong>
        </div>
    @endif
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
