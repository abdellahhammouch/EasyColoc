@component('mail::message')
# Invitation à rejoindre une colocation

Tu as été invité(e) à rejoindre la colocation **{{ $invitation->colocation->name }}**.

Invité(e) par : **{{ $invitation->inviter->name }}**

@component('mail::button', ['url' => $url])
Voir l’invitation
@endcomponent

Ce lien expire le : **{{ optional($invitation->expires_at)->format('Y-m-d H:i') }}**.

Merci,<br>
{{ config('app.name') }}
@endcomponent