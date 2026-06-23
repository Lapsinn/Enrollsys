<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — EnrollSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --maroon:#7a1f2b;
            --maroon-dark:#5c121c;
            --gold:#c9a227;
            --cream:#f7f5f2;
            --ink:#2b2b2b;
        }
        body{
            font-family:'Source Sans 3', system-ui, sans-serif;
            background:linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color:var(--ink);
            min-height:100vh;
        }
        h1,h2,h3,h4,h5,h6{ font-family:'Playfair Display', Georgia, serif; }
        .text-maroon{ color:var(--maroon) !important; }
        .btn-maroon{ background-color:var(--maroon); border-color:var(--maroon); color:#fff; }
        .btn-maroon:hover, .btn-maroon:focus{ background-color:var(--maroon-dark); border-color:var(--maroon-dark); color:#fff; }
        .nav-pills .nav-link.active{ background-color:var(--maroon); }
        .nav-pills .nav-link{ color:var(--maroon); font-weight:600; }
        .crest{
            width:64px; height:64px; border-radius:50%;
            background:radial-gradient(circle at 32% 30%, var(--gold), var(--maroon) 70%);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-family:'Playfair Display', serif; font-weight:700;
            border:2px solid var(--gold); margin:0 auto;
        }
        .auth-card{ max-width: 460px; }
    </style>
</head>
<body class="d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 auth-card">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>