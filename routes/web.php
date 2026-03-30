<?php

declare(strict_types=1);

use App\Http\Controllers\AuditExampleController;
use App\Http\Controllers\CaExampleController;
use App\Http\Controllers\CertificateExampleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoActionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/cas', [CaExampleController::class, 'index'])->name('cas.index');
Route::get('/cas/{uuid}', [CaExampleController::class, 'show'])->name('cas.show');

Route::get('/certificates', [CertificateExampleController::class, 'index'])->name('certificates.index');
Route::get('/certificates/{uuid}', [CertificateExampleController::class, 'show'])->name('certificates.show');

Route::get('/audit', [AuditExampleController::class, 'index'])->name('audit.index');

Route::post('/demo/issue-cert', [DemoActionController::class, 'issueCertificate'])->name('demo.issue');
Route::post('/demo/revoke-cert', [DemoActionController::class, 'revokeCertificate'])->name('demo.revoke');
Route::post('/demo/generate-crl', [DemoActionController::class, 'generateCrl'])->name('demo.crl');
