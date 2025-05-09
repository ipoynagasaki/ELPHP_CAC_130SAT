<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <style>
        /* Basic styling for better readability */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .user-container {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
        }

        .section-content {
            margin-left: 20px;
        }

        .user-info,
        .dog-info,
        .review-info,
        .transaction-info {
            padding: 5px 0;
        }
    </style>
</head>

<body>
    <h1>Users and Their Relationships</h1>

    @foreach($allUsers as $user)
    <div class="user-container">
        <div class="user-info">
            <strong>Name:</strong> {{ $user->name }} <br>
            <strong>Email:</strong> {{ $user->email }} <br>
            <strong>Status:</strong> {{ $user->status }} <br>
            <strong>Admin:</strong> {{ $user->admin ? 'Yes' : 'No' }} <br>
        </div>

        <!-- Display User's Dogs -->
        <div class="section-title">Dogs Owned by User:</div>
        <div class="section-content">
            @foreach($user->dogs as $dog)
            <div class="dog-info">
                <strong>Name:</strong> {{ $dog->name }} <br>
                <strong>Age:</strong> {{ $dog->age }} <br>
                <strong>Gender:</strong> {{ $dog->gender }} <br>
                <strong>Description:</strong> {{ $dog->description }} <br>
            </div>
            @endforeach
        </div>

       
        <div class="section-title">Favorite Dogs:</div>
        <div class="section-content">
            @foreach($user->favorites as $favorite)
            <div class="dog-info">
                <strong>Dog Name:</strong> {{ $favorite->dog->name }} <br>
            </div>
            @endforeach
        </div>

       
        <div class="section-title">Reviews by User:</div>
        <div class="section-content">
            @foreach($user->reviews as $review)
            <div class="review-info">
                <strong>Dog Name:</strong> {{ $review->dog->name }} <br>
                <strong>Rating:</strong> {{ $review->rating }} <br>
                <strong>Comment:</strong> {{ $review->comment }} <br>
            </div>
            @endforeach
        </div>

        <div class="section-title">Transactions:</div>
        <div class="section-content">
            @foreach($user->transactionsAsBuyer as $transaction)
            <div class="transaction-info">
                <strong>Buyer ID:</strong> {{ $transaction->buyer_id }} <br>
                <strong>Seller ID:</strong> {{ $transaction->seller_id }} <br>
                <strong>Dog:</strong> {{ $transaction->dog->name }} <br>
                <strong>Transaction Date:</strong> {{ $transaction->created_at }} <br>
            </div>
            @endforeach

            @foreach($user->transactionsAsSeller as $transaction)
            <div class="transaction-info">
                <strong>Buyer ID:</strong> {{ $transaction->buyer_id }} <br>
                <strong>Seller ID:</strong> {{ $transaction->seller_id }} <br>
                <strong>Dog:</strong> {{ $transaction->dog->name }} <br>
                <strong>Transaction Date:</strong> {{ $transaction->created_at }} <br>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</body>

</html>