@extends('layouts.guest')

@section('content')
<div class="text-center">
    <!-- Error Icon -->
    <div class="mb-4">
        <div class="mx-auto" style="width: 120px; height: 120px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.3);">
            <i class="mdi mdi-shield-lock-outline" style="font-size: 60px; color: white;"></i>
        </div>
    </div>

    <!-- Error Code -->
    <div class="mb-3">
        <h1 class="display-1 fw-bold" style="color: #dc3545; font-size: 6rem; line-height: 1; margin: 0;">403</h1>
    </div>

    <!-- Error Title -->
    <div class="mb-3">
        <h2 class="h4 fw-semibold text-dark mb-2">Access Denied</h2>
        <p class="text-muted mb-0" style="font-size: 1.1rem;">Only administrators can access this area</p>
    </div>

    <!-- Description -->
    <div class="mb-4">
        <p class="text-muted" style="max-width: 400px; margin: 0 auto; line-height: 1.6;">
            You don't have the necessary permissions to view this page. Please contact your administrator if you believe this is an error.
        </p>
    </div>

    <!-- Additional Help -->
    <div class="mt-5 pt-4" style="border-top: 1px solid #e9ecef;">
        <p class="text-muted small mb-2">
            <i class="mdi mdi-information-outline me-1"></i>
            Need help? Contact your system administrator
        </p>
        <p class="text-muted small mb-0">
            Error Code: 403 | {{ now()->format('Y-m-d H:i:s') }}
        </p>
    </div>
</div>

<style>
/* Custom animations for the error page */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-icon {
    animation: pulse 2s ease-in-out infinite;
}

.btn {
    transition: all 0.3s ease;
    transform: translateY(0);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%) !important;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .display-1 {
        font-size: 4rem !important;
    }
    
    .error-icon {
        width: 100px !important;
        height: 100px !important;
    }
    
    .error-icon i {
        font-size: 50px !important;
    }
}

/* Enhanced card styling for error page */
.card {
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border: none;
}

/* Smooth entrance animation */
.account-pages {
    animation: fadeInUp 0.6s ease-out;
}
</style>
@endsection

<!-- @section('footer-links')
<div class="mt-4 text-center">
    <p class="text-muted small">
        &copy; {{ date('Y') }} Syndeo. All rights reserved.
    </p>
</div>
@endsection -->