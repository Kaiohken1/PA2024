<!DOCTYPE html>
<html>
<head>
    <title>Subscription Form</title>
</head>
<body>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('subscribe') }}" method="POST" id="payment-form">
        @csrf
        <label for="plan">Choose Your Plan:</label>
        <select name="plan" id="plan">
            <option value="basic_plan">Basic Plan</option>
            <option value="premium_plan">Premium Plan</option>
        </select>
        
        <label for="interval">Choose Billing Interval:</label>
        <select name="interval" id="interval">
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <button type="submit">Subscribe</button>
    </form>
</body>
</html>
