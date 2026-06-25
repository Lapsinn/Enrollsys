<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnrollSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak], [x-cloak].d-flex { display: none !important; }
        :root{
            --maroon:#7a1f2b;
            --maroon-dark:#5c121c;
            --maroon-light:#9c2c3b;
            --gold:#c9a227;
            --cream:#f7f5f2;
            --ink:#2b2b2b;
        }
        body{
            font-family:'Source Sans 3', system-ui, sans-serif;
            background-color:var(--cream);
            color:var(--ink);
        }
        h1,h2,h3,h4,h5,h6{
            font-family:'Playfair Display', Georgia, serif;
        }
        .page-title{
            display:inline-block;
            color:var(--maroon);
            font-weight:700;
            border-bottom:3px solid var(--maroon);
            padding-bottom:.4rem;
            margin-bottom:1.75rem;
        }
        .text-maroon{ color:var(--maroon) !important; }
        .bg-maroon{ background-color:var(--maroon) !important; }
        .bg-maroon-dark{ background-color:var(--maroon-dark) !important; }
        .btn-maroon{
            background-color:var(--maroon);
            border-color:var(--maroon);
            color:#fff;
        }
        .btn-maroon:hover, .btn-maroon:focus{
            background-color:var(--maroon-dark);
            border-color:var(--maroon-dark);
            color:#fff;
        }
        .btn-outline-maroon{
            color:var(--maroon);
            border-color:var(--maroon);
            background:#fff;
        }
        .btn-outline-maroon:hover{
            background-color:var(--maroon);
            color:#fff;
        }
        .badge-maroon{
            background-color:var(--maroon);
            color:#fff;
            font-weight:500;
        }
        .border-dashed { border-style: dashed !important; }

        /* ---- Header ---- */
        .utility-bar{
            background:#e9e4dd;
            font-size:.82rem;
        }
        .utility-bar a{
            color:#4a4a4a;
            text-decoration:none;
            margin-right:1.5rem;
            font-weight:500;
        }
        .utility-bar a:last-child{ margin-right:0; }
        .utility-bar a:hover{ color:var(--maroon); }

        .brand-row{ background:#fff; }
        .crest{
            width:60px;
            height:60px;
            flex-shrink:0;
            border-radius:50%;
            background:radial-gradient(circle at 32% 30%, var(--gold), var(--maroon) 70%);
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-family:'Playfair Display', serif;
            font-weight:700;
            font-size:1.1rem;
            border:2px solid var(--gold);
        }
        .brand-title{
            color:var(--maroon);
            font-weight:800;
            font-size:1.6rem;
            margin-bottom:0;
            line-height:1.1;
        }
        .brand-tagline{
            color:#6b6b6b;
            font-size:.85rem;
            letter-spacing:.03em;
        }

        .nav-row{
            background:#fff;
            border-top:1px solid #eee;
            border-bottom:3px solid var(--maroon);
        }
        .nav-row .nav-link{
            color:var(--maroon);
            font-weight:700;
            letter-spacing:.04em;
            text-transform:uppercase;
            font-size:.82rem;
            padding:.85rem .9rem;
        }
        .nav-row .nav-link:hover{ color:var(--maroon-dark); }

        /* ---- Hero ---- */
        .hero-band{
            background:linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color:#fff;
        }

        footer.site-footer{
            background:var(--maroon-dark);
            color:#e7d9da;
        }
        footer.site-footer a{ color:#f0d9da; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <main class="container mt-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="site-footer mt-5 py-4 text-center">
        <p class="small mb-0">&copy; 2026 EnrollSys — School Name, Inc. All rights reserved. Address Address</p>
    </footer>
</body>
</html>