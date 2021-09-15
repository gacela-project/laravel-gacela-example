<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Products</title>
</head>
<body>
@forelse ($products as $product)
    <li>{{ $product->name }} - {{ $product->price }}</li>
@empty
    <li>No products have been found</li>
@endforelse

@if(Session::has('success'))
    <p>{{Session::get('success') }}</p>
@endif
</body>
</html>
