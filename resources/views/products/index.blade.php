<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body>

    <h1>Product Management</h1>
    <form id="addProductForm" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity in Stock</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price Per Item</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2>Products</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity in Stock</th>
                <th>Price Per Item</th>
                <th>Date Time Submitted</th> 
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody id="productBody">
            @php $totalValue = 0; @endphp
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>{{ $product['price'] }}</td>
                    <td>{{ $product['datetime_submitted'] }}</td>
                    <td>{{ $product['total_value'] }}</td>
                
                </tr>
                @php  $totalValue += $product['total_value']; @endphp
            @endforeach

            <tr>
                <td colspan="4"><strong>Total: </strong></td>
                <td><strong>{{ $totalValue }}</strong></td>
            </tr>
        </tbody>
    </table>
    
    <script>
        var productData;
        var newRow;
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/add',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {

                        productData = response.data[response.data.length - 1]; 
                        newRow = `
                            <tr>
                                <td>${productData.name}</td>
                                <td>${productData.quantity}</td>
                                <td>${productData.price}</td>
                                <td>${productData.datetime_submitted}</td>
                                <td>${productData.total_value}</td>
                            </tr>
                        `;

                        $('#productBody').find('tr:last').before(newRow);

                        let total = parseFloat($('#productBody tr:last td:last').text()) || 0;
                        total += productData.total_value;
                        $('#productBody tr:last td:last').html('<strong>'+total.toFixed(2)+'</strong>');

                        // Clear the form fields
                        $('#productForm')[0].reset();
                    }
                },
                error: function(err) {
                    alert('An error occurred. Please try again.');
                    console.error(err);
                }
            });
        });

    </script>


</body>