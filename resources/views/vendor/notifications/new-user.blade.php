<h2> Nuevo usuario </h2>
<br>
<p> El usuario <strong>{{ $data['name'] }}</strong> ha sido creado con éxito. </p>
<p> Su contraseña es: {{ $data['password'] }} </p>
<p> Por favor, cambie su contraseña en su primer inicio de sesión. </p>
Accede en el siguiente link<br><br>
<x-mail::button :url="$url">
Acceder
</x-mail::button>
<p> Gracias. </p>
