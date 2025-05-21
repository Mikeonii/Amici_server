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
    <div class="">

        <h1 class="mb-8 mt-4" style='margin-bottom:30px'>Daily Sales Report: {{$date}}</h1>
        <hr>
        <h3>Monthly Subscriptions</h3>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Account Name</th>
                    <th>Transaction</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Posted By</th>

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
                        <td>{{$sale->payment_method}}</td>
                        <td>{{$sale->created_at}}</td>
                        <td>{{$sale->posted_by}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Total Gcash: {{number_format($sales->where('payment_method', '=', 'Gcash')->sum('amount')) }}</p>
        <p>Total Cash: {{number_format($sales->where('payment_method', '=', 'Cash')->sum('amount')) }}</p>
        <h4 class=>Total: <span style="font-weight: bold; color:green">{{number_format($sales->sum('amount'))}}</span></h4>
        <br>
        <br>
        <h3>Walk-in Sessions</h3>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Customer Name</th>
                    <th>Transaction</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Posted By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($walk_in_sales as $key => $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td> 
                        <td>{{$sale->customer_name}}</td>
                        <td>Walk-in Session</td>
                        <td>{{$sale->amount_paid}}</td>
                        <td>{{$sale->payment_method}}</td>
                        <td>{{$sale->created_at}}</td>
                        <td>{{$sale->posted_by}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
  
        <p>Total Gcash: {{number_format($walk_in_sales->where('payment_method', '=', 'Gcash')->sum('amount_paid')) }}</p>
        <p>Total Cash: {{number_format($walk_in_sales->where('payment_method', '=', 'Cash')->sum('amount_paid')) }}</p>
        <h4>Total: <span style="font-weight: bold; color:green; margin-top:-20px">{{number_format($walk_in_sales->sum('amount_paid'))}}</span></h4>
        <br>
        <br>
        <h3>Expenses</h3>
         <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Particulars</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Posted by</th>
                </tr>
            </thead>
               <tbody>
                @foreach($expenses as $key => $expense)
                    <tr>
                        <td>{{ $loop->iteration }}</td> 
                        <td>{{$expense->particulars}}</td>
                        <td>{{$expense->amount}}</td>
        
                        <td>{{$expense->category}}</td>
                        <td>{{$expense->created_at}}</td>
                        <td>{{$expense->posted_by}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            
         </table>
        <h4>Total: <span style="font-weight: bold; color:red; margin-top:-20px">{{number_format($expenses->sum('amount'))}}</span></h4>

     
        <hr>
        <h4>Total Net: <strong>{{number_format($walk_in_sales->sum('amount_paid')+$sales->sum('amount')) }}</strong></h4>
        <h4>Less Expenses: <strong>{{number_format($expenses->sum('amount')) }}</strong></h4>
        <hr>
        <h3 style='color:blue'>Gross: <strong>{{number_format(($walk_in_sales->sum('amount_paid')+$sales->sum('amount'))-$expenses->sum('amount')) }}</strong></h3>
    </div>
</body>
</html>
