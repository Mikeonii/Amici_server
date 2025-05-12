<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container">

        <h1 class=mb-4>Sales Report: {{$date}}</h1>
        <h3>Monthly Subscriptions and Item Sales</h3>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Account Name</th>
                    <th>Transaction</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $key => $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td> 
                        @if($sale->account)
                        <td>{{$sale->account->name}}</td>
                        @else 
                        <td style="color:red">Account Deleted</td>
                        @endif

                        @if($sale->item)
                        <td>{{$sale->item->item_name}}</td>

                        @else
                        <td style=color:red>Item Deleted</td>
                        @endif

                        <td>{{$sale->amount}}</td>
                        <td>{{$sale->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
       
        <h3>Walk-in Sessions</h3>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Customer Name</th>
                    <th>Transaction</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($walk_in_sales as $key => $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td> 
                        <td>{{$sale->customer_name}}</td>
                        <td>Walk-in Session</td>
                        <td>{{$sale->amount_paid}}</td>
                        <td>{{$sale->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h4 class=mt-5>Total Monthly Subscribers Collection: <span style="font-weight: bold; color:green">{{number_format($sales->sum('amount'))}}</span></h4>

        <h4>Total Walk-in Sessions Collection: <span style="font-weight: bold; color:green; margin-top:-20px">{{number_format($walk_in_sales->sum('amount_paid'))}}</span></h4>
        <hr>
        <h3>Total Collection: <strong>{{number_format($walk_in_sales->sum('amount_paid')+$sales->sum('amount')) }}</strong></h3>
    </div>
</body>
</html>
