<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket PDF</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center;}
        .container { width: 90%; padding: 20px; border: 1px solid #ddd; }
        h2 { text-align: center; }
        .section { margin-bottom: 15px; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Event Ticket</h2>

        <div class="section">
            <p><span class="bold">Ticket Code:</span> {{ $ticket_code }}</p>
            <p><span class="bold">Reservation ID:</span> {{ $reservation_id }}</p>
        </div>

        <div class="section">
            <h3>User Information</h3>
            <p><span class="bold">Name:</span> {{ $user->name }}</p>
            <p><span class="bold">Email:</span> {{ $user->email }}</p>
            <p><span class="bold">Phone:</span> {{ $user->tel_no }}</p>
        </div>

        <div class="section">
            <h3>Event Information</h3>
            <p><span class="bold">Name:</span> {{ $event->name }}</p>
            <p><span class="bold">Description:</span> {{ $event->description }}</p>
            <p><span class="bold">Start Date:</span> {{ $event->start_date }}</p>
            <p><span class="bold">End Date:</span> {{ $event->end_date }}</p>
        </div>

        <div class="section">
            <h3>Venue Information</h3>
            <p><span class="bold">Name:</span> {{ $venue->name }}</p>
            <p><span class="bold">Address:</span> {{ $venue->address }}</p>
        </div>

        <div class="section">
            <h3>Seat Information</h3>
            <p><span class="bold">Section:</span> {{ $seat->section ?? 'N/A' }}</p>
            <p><span class="bold">Row:</span> {{ $seat->row }}</p>
            <p><span class="bold">Number:</span> {{ $seat->number }}</p>
        </div>

        <p style="text-align: center; font-size: 12px;">Thank you for your purchase!</p>
    </div>
</body>
</html>
