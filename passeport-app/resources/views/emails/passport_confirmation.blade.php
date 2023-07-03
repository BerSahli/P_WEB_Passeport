@component('mail::message')
# Confirmation de modification de passeport

Veuillez cliquer sur le bouton ci-dessous pour confirmer les modifications apportées au passeport.

@component('mail::button', ['url' => $confirmationUrl])
Confirmer les modifications
@endcomponent

Si vous n'avez pas demandé de modification de passeport, vous pouvez ignorer cet email.

Merci,<br>
ETML
@endcomponent
