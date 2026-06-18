<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$job = DB::table('failed_jobs')->where('uuid', '43976d62-130c-4bbf-a778-4a23b0a1054c')->first();
if ($job) {
    echo substr($job->exception, 0, 1500);
} else {
    echo "Job not found";
}
