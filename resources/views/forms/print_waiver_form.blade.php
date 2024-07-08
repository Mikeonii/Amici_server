<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Document</title>
</head>
<body onload="window.print()">
    <div class="container">
        <img src="{{ asset('jc_fitness_banner.jpg') }}" width="100%">


<h3 style="color:purple; margin-bottom:-10px;" class="mt-3">Member Information</h3>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-weight:bold">{{$acc->name}}</td>
            <td style="font-weight:bold">{{$acc->gender}}</td>
            <td style="font-weight:bold">{{$acc->birth_date}}</td>
            <td style="font-weight:bold">{{$acc->address}}</td>
            <td style="font-weight:bold">{{$acc->phone_number}}</td>
        </tr>
    </tbody>
</table>


<p>I, the undersigned, understand and acknowledge that the use of the gym facilities, equipment, and participation in gym activities involve inherent risks, including, but not limited to, physical injury, illness, or death. I voluntarily choose to participate in these activities and accept the risks associated with them.</p>

<p>In consideration of being allowed to participate in gym activities and use the gym's facilities and equipment, I hereby release, waive, discharge, and covenant not to sue jc fitness gym its owners, employees, agents, and affiliates from any and all liability, claims, demands, actions, or causes of action related to any loss, damage, injury, or death that may be sustained by me while participating in such activities or using the gym facilities and equipment, whether caused by the negligence of the releases or otherwise.</p>

<p>I consent to receive medical treatment deemed necessary if I am injured or require medical attention during my participation in gym activities. I understand and agree that I am solely responsible for all costs related to such medical treatment.</p>

<p>I agree that if any portion of this waiver is found to be void or unenforceable, the remaining portions shall remain in full force and effect.</p>

<p>I have read this waiver of liability, assumption of risk, and indemnity agreement, fully understand its terms, and understand that I am giving up substantial rights, including my right to sue. I acknowledge that I am signing the agreement freely and voluntarily and intend by my signature to be a complete and unconditional release of all liability to the greatest extent allowed by law.</p>

<hr class="mt-4 mb-4">

<p>
Signature:
- Member Signature: _______________________
- Date: _______________________
</p>

<p>
For Minors (under 18 years old):
- Parent/Guardian Name: _______________________
- Parent/Guardian Signature: _______________________
- Date: _______________________
</p>

<p style="font-size:12px; color:grey; text-align:center; margin-top:20px;">This is a system generated form. JC Fitness Gym Pass System</p>
</div>
</body>
</html>
