
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $pageTitle ?? '' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bento Group Indonesia" name="keywords">
    <meta content="Bento Group Indonesia merupakan gabungan dari beberapa perusahaan bergerak sebagai pengelola dari berbagai dana investasi dari para investor. Kami bergerak di bidang Real Estate hingga kafe & Co-working. Saat ini sudah banyak cabang usaha di bawah pengelolaan Bento Group Indonesia." name="description">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4569086114807351" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@1,600;1,700;1,800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

</head>

<body>
    <div class="row mt-3">
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Unit price</th>
                        <th>Tax</th>
                        <th>Discount</th>
                        <th>Total without tax</th>
                        <th>Total with tax</th>
                        <th>total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Items as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>$ {{ $row->unit_price }}</td>
                        <td>$ {{ $row->tax }}</td>
                        <td>$ {{ $row->discount }}</td>
                        <td>$ {{ $row->total_without_tax }}</td>
                        <td>$ {{ $row->total_with_tax }}</td>
                        <td>$ {{ $row->total }}</td>
                        {{-- <td><a href="{{route('export-PDF', ['id' => {{$row->id}}])}}">Generate Invoice</a></td> --}}
                        <td><a href="{{ route('export-PDF', ['id' => $row->id]) }}">Generate Invoice</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <form id="formItem" method="POST" action="{{ route('store') }}">
                @csrf
                <div class="form-group">
                  <label for="">Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                  <label for="">Quantity</label>
                  <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
                </div>
                <div class="form-group">
                  <label for="">Unit Price</label>
                  <input type="number" class="form-control" id="unit_price" name="unit_price" placeholder="unit_price" required>
                </div>
                <div class="form-group">
                  <label for="">Tax</label>
                  <select class="form-control" id="tax" name="tax" required>
                    <option value="">Select</option>
                    <option value="0">0 %</option>
                    <option value="1">1 %</option>
                    <option value="5">5 %</option>
                    <option value="10">10 %</option>
                  </select>
                </div>
                <div class="form-group">
                    <label for="">Discount</label>
                    <select class="form-control" id="discount_type" name="discount_type">
                        <option value="">Select</option>
                        <option value="Flat">Flat</option>
                        <option value="Percentage">Percentage</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Discount value</label>
                    <input type="number" class="form-control" id="discount_value" name="discount_value" placeholder="">
                </div>
                <div class="form-group">
                    <label for="">Total amount: <span id="total_amount"></span></label>
                </div>
                <button type="submit" class="btn btn-success">Add Item</button>
              </form>
        </div>
    </div>
    <script>
        // $("#formItem").submit(function( event ) {
        //     event.preventDefault()
        //     var formData = $(this).serialize();
        //     $.ajax({
        //         url: $(this).attr('action'),
        //         type: 'POST',
        //         data: formData,
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });
        // });
        $('#discount_value').change(function(){
            // alert('Don')
            // (Price x Quantity) + (Tax x Price x Quantity)

            var price = parseFloat($("#unit_price").val());
            var quantity = parseInt($("#quantity").val());
            var tax = parseFloat($("#tax").val()) / 100;
            var discount = this.value;
            var discountType = $("#discount_type").val();


            var totalBeforeDiscountAndTax = price * quantity;
            if (discountType === "Percentage") {
                totalBeforeDiscountAndTax = totalBeforeDiscountAndTax * (1 - (discount / 100));
            } else if (discountType === "Flat") {
                totalBeforeDiscountAndTax = totalBeforeDiscountAndTax - discount;
            }

            var total = totalBeforeDiscountAndTax * (1 + tax);

            $("#total_amount").text(total.toFixed(2));
        });
    </script>
