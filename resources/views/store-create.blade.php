
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Create a New Store</h1>

    <form action="{{ route('store.create') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf

        <!-- Store Name Input -->
        <div class="mb-3">
            <label for="name" class="form-label">Store Name</label>
            <input
                type="text"
                name="name"
                id="name"
                class="form-control"
                placeholder="Enter store name"
                required>
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Image Upload Input -->
        <div class="mb-3">
            <label for="image" class="form-label">Upload Store Image</label>
            <input
                type="file"
                name="image"
                id="image"
                class="form-control"
                accept="image/*"
                required>
            @error('image')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Create Store</button>
        </div>
    </form>
</div>
</body>
</html>
