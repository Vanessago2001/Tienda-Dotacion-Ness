<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel')</title>
    <link rel="icon" href="{{ asset('images/logo_happy_store.png') }}">

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body{ min-height:100vh; background:linear-gradient(135deg,#0d3f3c,#1c7a74,#40E0D0); padding:30px; }

        .navbar{
            background:rgba(255,255,255,.90); backdrop-filter:blur(12px); border-radius:20px;
            padding:20px 30px; margin-bottom:25px; box-shadow:0 10px 30px rgba(0,0,0,.06);
            display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;
        }
        .logo{ font-size:22px; font-weight:700; color:#0d3f3c; }
        .nav-links{ display:flex; gap:12px; flex-wrap:wrap; }
        .nav-links a, .nav-links button{
            text-decoration:none; padding:10px 18px; border-radius:12px; font-weight:600;
            transition:.3s; border:none; cursor:pointer; font-size:15px;
        }
        .btn-dashboard{ background:#e6fbf8; color:#0d3f3c; }
        .btn-modulo{ background:#d7f7f2; color:#0f766e; }
        .btn-nuevo{ background:linear-gradient(135deg,#14b8a6,#2dd4bf); color:#fff; }
        .nav-links a:hover, .nav-links button:hover{ transform:translateY(-2px); }

        .container{
            background:rgba(255,255,255,.92); backdrop-filter:blur(12px); border-radius:24px;
            padding:30px; box-shadow:0 10px 30px rgba(0,0,0,.06);
        }

        .success{ background:#dcfce7; color:#166534; padding:15px; border-radius:12px; margin-bottom:20px; border-left:5px solid #22c55e; }
        .error{ background:#fee2e2; color:#991b1b; padding:15px; border-radius:12px; margin-bottom:20px; border-left:5px solid #ef4444; }
        .error ul{ margin-left:18px; }

        .header-card{ margin-bottom:20px; }
        .header-card h1{ margin:0; color:#1e293b; font-size:28px; font-weight:700; }
        .header-card p{ margin-top:6px; color:#64748b; }

        .stats{ display:flex; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
        .stat-card{ flex:1; min-width:180px; background:#f0fdfa; border:1px solid #ccfbf1; border-radius:16px; padding:16px; }
        .stat-card h3{ font-size:12px; color:#0f766e; margin-bottom:6px; text-transform:uppercase; }
        .stat-card p{ font-size:24px; font-weight:700; color:#0f172a; }

        .filtros{
            display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:12px; align-items:end;
            margin-bottom:22px; background:#f8fafc; padding:16px; border-radius:16px; border:1px solid #e2e8f0;
        }
        .filtros .acciones{ display:flex; gap:8px; }

        label{ font-size:13px; font-weight:600; color:#475569; display:block; margin-bottom:4px; }
        input, select, textarea{
            width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:10px; margin-bottom:2px;
        }
        input:focus, select:focus, textarea:focus{ outline:none; border-color:#14b8a6; }

        .form-card{ max-width:560px; }
        .form-card .campo{ margin-bottom:16px; }
        .form-actions{ display:flex; gap:12px; margin-top:20px; }

        .btn{ text-decoration:none; padding:9px 16px; border-radius:10px; font-weight:600; font-size:14px; display:inline-block; border:none; cursor:pointer; transition:.2s; }
        .btn:hover{ transform:translateY(-2px); }
        .btn-primary{ background:linear-gradient(135deg,#14b8a6,#2dd4bf); color:#fff; }
        .btn-secondary{ background:#f1f5f9; color:#475569; }
        .btn-ver{ background:#e6fbf8; color:#0d3f3c; }
        .btn-edit{ background:#ccfbf1; color:#0f766e; }
        .btn-perm{ background:#ccfbf1; color:#0f766e; }
        .btn-on{ background:#dcfce7; color:#166534; }
        .btn-off{ background:#fee2e2; color:#991b1b; }
        .btn-delete{ background:#fee2e2; color:#dc2626; }

        .table-modern{ width:100%; border-collapse:collapse; }
        .table-modern thead{ background:#ecfeff; }
        .table-modern th{ padding:13px 14px; text-align:left; color:#0f766e; font-weight:700; border-bottom:1px solid #ccfbf1; font-size:14px; }
        .table-modern td{ padding:11px 14px; border-bottom:1px solid #f1f5f9; font-size:14px; }
        .table-modern tbody tr:hover{ background:#f0fdfa; transition:.3s; }

        .badge{ padding:5px 12px; border-radius:20px; font-size:12px; font-weight:700; display:inline-block; }
        .badge-green{ background:#dcfce7; color:#166534; }
        .badge-red{ background:#fee2e2; color:#991b1b; }
        .badge-blue{ background:#e0f2fe; color:#0369a1; }
        .badge-amber{ background:#fef3c7; color:#b45309; }
        .badge-gray{ background:#f1f5f9; color:#475569; }

        .acciones-fila{ display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

        .pagination{ display:flex; gap:12px; align-items:center; justify-content:center; margin-top:22px; }
        .pagination .page{ text-decoration:none; padding:8px 16px; border-radius:10px; font-weight:600; background:#ccfbf1; color:#0f766e; }
        .pagination .disabled{ background:#f1f5f9; color:#94a3b8; }
        .pagination .page-info{ color:#475569; font-weight:600; }

        .empty{ text-align:center; padding:40px; color:#94a3b8; }

        @media(max-width:768px){
            body{ padding:15px; }
            .navbar{ flex-direction:column; align-items:flex-start; }
            .nav-links{ width:100%; }
        }
    </style>
</head>
<body>

    <nav class="navbar" style="position:relative; z-index:3000;">
        <div class="logo" style="display:flex; align-items:center; gap:16px;"><x-menu-boton /> @yield('titulo', 'Panel')</div>
        <div class="nav-links">
            @yield('nav')
        </div>
    </nav>

    <div class="container">
        @if(session('success'))<div class="success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="error">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </div>

</body>
</html>
