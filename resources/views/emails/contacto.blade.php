<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body>
    <h2>Has recibido un nuevo mensaje desde PetCare:</h2>
    <p><strong>De:</strong> {{ $details['name'] }}</p>
    <p><strong>Email:</strong> {{ $details['email'] }}</p>
    <hr>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $details['message'] }}</p>
</body>
</html>