<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            color: #424242;
        }
        .store {
            font-size: 1.125rem;
            line-height: 1.75rem;
            font-weight: 600;
            color: #424242;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .location {
            font-size: 0.75rem;
            line-height: 1.75rem;
            font-weight: 600;
            color: #424242;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        label {
            font-size: 0.75rem;
            color: #424242;
        }
        p.text {
            font-size: 0.75rem;
            color: #424242;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th {
            padding: .4rem;
            color: #6F6F6F;
        }
        table, td {
            padding: .4rem;
            color: #6F6F6F;
        }
        table, th, td {
            border: 1px solid #F2F2F2;
        }
        .action-approve {
            display: flex;
            margin-top: 1rem;
        }
        .btn-approve {
            text-decoration: none;
            background-color: #2699FF;
            color: #FFFFFF !important;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
        }
        .btn-reject {
            text-decoration: none;
            background-color: #F44C4F;
            color: #FFFFFF !important;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
        }
        .btn-detail {
            text-decoration: none;
            background-color: #6f6f6f;
            color: #FFFFFF !important;
            border-radius: 4px;
            padding: 0.5rem 1rem;
        }
        .container {
            text-align: center;
            border: 1px solid #F2F2F2;
            border-radius: 8px;
            padding: 3rem;
            background: #F2F2F2;
        }
        .container a {
            text-decoration: none;
            background-color: #2699FF;
            color: #FFFFFF !important;
            border-radius: 4px;
            padding: 0.9rem 1.4rem;
            font-size: 1rem;
        }
        .container p {
            font-weight: 600;
            margin-bottom: 0px;
            margin-top: 2rem;
            color: #424242;
        }
    </style>
</head>
<body>
    <div>
        <h2 class="store">{{ $id }}</h2>
        <h2 class="store">{{ $store }}</h2>
        <p class="location">{{ $location }}</p>
        <p class="location">{{ $contact }}</p>
    </div>
    <div>
        <label class="label">Billing Date:</label>
        <p class="text">{{ $billingDate }}</p>
    </div>
    <!-- <div>
        <label class="label">Total Item:</label>
        <p class="text">{{ $totalItem }}</p>
    </div>
    <div>
        <label class="label">Total Price:</label>
        <p class="text">{{ $totalPrice }}</p>
    </div> -->

    @if(is_array($dataItem))
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>PRODUCT NAME</th>
                <th>SELL-THRU PRICE</th>
                <th>PRICE (RRP)</th>
                <th>QTY</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataItem as $value)
            <tr>
                <td>{{ $value->SKU }}</td>
                <td>{{ $value->Product }}</td>
                <td>{{ number_format($value->ThruPrice,0,",",".") }}</td>
                <td>{{ number_format($value->UnitPrice,0,",",".") }}</td>
                <td>{{ $value->Total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="action-approve">
        <a href="{{ $urlApprove }}" class="btn-approve">
            Approve
        </a>
        <a href="{{ $urlReject }}" class="btn-reject">
            Reject
        </a>
        <a href="{{ $url }}" class="btn-detail">
            View Detail
        </a>
    </div>
    @else
    <div class="container">
        <a href="{{ $dataItem }}">Login DAMS APP</a>
        <p>Harap lakukan proses approval pada halaman WEB melalui link diatas</p>
    </div>
    @endif
</body>
</html>