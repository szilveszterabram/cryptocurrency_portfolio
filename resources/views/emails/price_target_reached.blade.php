<!DOCTYPE html>
<html>
    <head>
        <title>Price Target Reached</title>
    </head>
    <body>
        <p>Target {{ $priceObservation->target }} reached on {{ $asset['name'] }}, now at: {{ $asset['price_usd'] }}</p>
    </body>
</html>
