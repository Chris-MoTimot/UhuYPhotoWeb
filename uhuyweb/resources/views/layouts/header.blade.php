<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Galeri Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-brand { font-weight: 700; color: #0d6efd !important; }
        .card { transition: all 0.3s ease; border: none; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .card-img-top { height: 200px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .modal-content { border: none; border-radius: 12px; overflow: hidden; }
        .bg-black-custom { background-color: #000; }
        footer { background: white; padding: 20px 0; border-top: 1px solid #dee2e6; margin-top: 50px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">Uhuy PhotoWeb</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto"></ul>
                <div class="d-flex gap-2">
                    <a href="/register" class="btn btn-outline-primary px-4">Daftar</a>
                    <a href="/login" class="btn btn-primary px-4">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="alert alert-warning text-center rounded-0 m-0 border-0" role="alert">
        <i class="fa-solid fa-circle-info me-2"></i>
        Anda masih menggunakan akun <strong>guest</strong>. 
        <a href="/register" class="alert-link text-decoration-none">Daftar</a> atau 
        <a href="/login" class="alert-link text-decoration-none">Masuk</a> untuk fitur lengkap!
    </div>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>