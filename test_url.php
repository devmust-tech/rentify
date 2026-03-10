<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

$org = App\Models\Organization::where('slug', 'metro')->first();
if (!$org) {
    $org = App\Models\Organization::withoutGlobalScopes()->first();
}
echo "Org: {$org->slug}\n";

app()->instance('currentOrganization', $org);
Illuminate\Support\Facades\URL::defaults(['org' => $org->slug]);

$landlord = App\Models\Landlord::withoutGlobalScopes()->where('organization_id', $org->id)->first();
echo "Landlord: {$landlord?->id}\n";

if ($landlord) {
    $url = Illuminate\Support\Facades\URL::temporarySignedRoute(
        'landlord.invitation.show',
        now()->addHours(72),
        ['org' => $org->slug, 'landlord' => $landlord->id]
    );
    echo "URL: {$url}\n";
}
