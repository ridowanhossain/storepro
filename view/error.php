<?php require_once 'includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div class="error-template">
                <h1 class="display-1">Oops!</h1>
                <h2 class="display-4">404 Not Found</h2>
                <div class="error-details mb-4">
                    Sorry, an error has occurred. The requested page was not found!
                </div>
                <div class="error-actions">
                    <a href="/store-management/" class="btn btn-primary btn-lg">
                        <i class="bi bi-house-fill"></i> Back to Home
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg ml-2">
                        <i class="bi bi-arrow-left"></i> Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .error-template {
        padding: 40px 15px;
    }
    .error-actions {
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .error-actions .btn {
        margin-right: 10px;
    }
    .display-1 {
        color: #dc3545;
    }
    .display-4 {
        color: #6c757d;
    }
    .error-details {
        color: #6c757d;
        font-size: 1.2rem;
    }
</style>

<?php require_once 'includes/footer.php'; ?> 