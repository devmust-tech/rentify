<x-mail::message>
# Your Workspace is Approved! 🎉

Hi {{ $organization->owner?->name ?? 'there' }},

Great news — your **{{ $organization->name }}** workspace on {{ config('app.name') }} has been approved by our team.

You can now log in and start managing your properties:

<x-mail::button :url="$organization->subdomainUrl() . '/login'">
Go to Your Workspace
</x-mail::button>

Your workspace URL:
**{{ $organization->subdomainUrl() }}**

If you have any questions, simply reply to this email.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
