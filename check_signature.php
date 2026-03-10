<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

$urls = [
    'http://metro.rentify.test/invitation/landlord/01kk1hc53mh11kmcdz1ebqsvk7?expires=1773058730&signature=aba4e6752d25001a2f00ec05a3bdd80218ddc1b264076d93f88a8d93e0e6fc14',
    'http://metro.rentify.test/invitation/landlord/01kk1hmb491kfrdf0c447k77zx',
];

foreach ($urls as $u) {
    $req = Request::create($u, 'GET');
    $abs = URL::hasValidSignature($req, true);
    $rel = URL::hasValidSignature($req, false);
    echo "URL: $u\n";
    echo "  hasValidSignature(abs): ".($abs ? 'true' : 'false')."\n";
    echo "  hasValidSignature(rel): ".($rel ? 'true' : 'false')."\n\n";
}
