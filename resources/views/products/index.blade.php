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


    <form id="editProductForm" class="mb-4" style="display: none;">
        @csrf
        <input type="hidden" id="editId" name="id">
        <div class="mb-3">
            <label for="editName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="editName" name="name" required>
        </div>
        <div class="mb-3">
            <label for="editQuantity" class="form-label">Quantity in Stock</label>
            <input type="number" class="form-control" id="editQuantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="editPrice" class="form-label">Price Per Item</label>
            <input type="number" class="form-control" id="editPrice" name="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
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
                <th>Edit</th>
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
                    <td><button class="btn btn-warning edit-btn"  data-id="{{$loop->index}}">Edit</button></td>

                
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
       
        $('#addProductForm').on('submit', function(e) {
            var productData;
            var newRow;

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

                        let total = parseInt($('#productBody tr:last td:last').text()) || 0;
                        total += parseInt(productData.total_value);
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

        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            
            const name = row.find('td:nth-child(1)').text();
            const quantity = row.find('td:nth-child(2)').text();
            const price = row.find('td:nth-child(3)').text();
            
            $('#editProductForm').show();
            $('#editId').val(id);
            $('#editName').val(name);
            $('#editQuantity').val(quantity);
            $('#editPrice').val(price);
        });

        
        $('#editProductForm').on('submit', function(e) {

            var updatedProduct;
            var row;

            e.preventDefault();
            $.ajax({
                url: '/edit',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        updatedProduct = response.data[$('#editId').val()];
                        row = $('#productBody').find('tr').eq($('#editId').val());

                        row.find('td:nth-child(1)').text(updatedProduct.name);
                        row.find('td:nth-child(2)').text(updatedProduct.quantity);
                        row.find('td:nth-child(3)').text(updatedProduct.price);
                        row.find('td:nth-child(5)').text(updatedProduct.total_value);

                        // Hide the edit form and reset it
                        $('#editProductForm').hide().trigger('reset');
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