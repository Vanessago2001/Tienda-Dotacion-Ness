<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestión de Productos')</title>
    <link rel="icon" href="{{ asset('images/logo_happy_store.png') }}">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            min-height:100vh;
            background: linear-gradient(
                135deg,
                #0d3f3c,
                #1c7a74,
                #40E0D0
            );
            padding:30px;
        }

        .navbar{
            background: rgba(255,255,255,.90);
            backdrop-filter: blur(12px);
            border-radius:20px;
            padding:20px 30px;
            margin-bottom:25px;
            box-shadow:0 10px 30px rgba(0,0,0,.06);

            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:15px;
        }

        .logo{
            font-size:24px;
            font-weight:700;
            color:#0f766e;
        }

        .nav-links{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }

        .nav-links a{
            text-decoration:none;
            padding:10px 18px;
            border-radius:12px;
            font-weight:600;
            transition:.3s;
        }

        .btn-dashboard{
            background:#d9f7f3;
            color:#0f766e;
        }

        .btn-inventario{
            background:#e6fbf8;
            color:#167a74;
        }

        .btn-nuevo{
            background:linear-gradient(
                135deg,
                #14b8a6,
                #2dd4bf
            );
            color:white;
        }

        .nav-links a:hover{
            transform:translateY(-2px);
        }

        .container{
            background: rgba(255,255,255,.90);
            backdrop-filter: blur(12px);
            border-radius:24px;
            padding:30px;
            box-shadow:0 10px 30px rgba(0,0,0,.06);
        }

        .success{
            background:#dcfce7;
            color:#166534;
            padding:15px;
            border-radius:12px;
            margin-bottom:20px;
            border-left:5px solid #22c55e;
        }

        .error{
            background:#fee2e2;
            color:#991b1b;
            padding:15px;
            border-radius:12px;
            margin-bottom:20px;
            border-left:5px solid #ef4444;
        }

        input,
        select,
        textarea{
            width:100%;
            padding:12px;
            border:1px solid #b7f3ec;
            border-radius:12px;
            margin-top:8px;
            margin-bottom:16px;
            transition:.3s;
        }

        input:focus,
        select:focus,
        textarea:focus{
            outline:none;
            border-color:#40E0D0;
            box-shadow:0 0 0 4px rgba(64,224,208,.15);
        }

        button{
            background:linear-gradient(
                135deg,
                #14b8a6,
                #2dd4bf
            );
            color:white;
            border:none;
            padding:12px 20px;
            border-radius:12px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        button:hover{
            transform:translateY(-2px);
            box-shadow:0 10px 20px rgba(64,224,208,.25);
        }

        @media(max-width:768px){

            body{
                padding:15px;
            }

            .navbar{
                flex-direction:column;
                align-items:flex-start;
            }

            .nav-links{
                width:100%;
            }

            .nav-links a{
                flex:1;
                text-align:center;
            }

        }

    </style>

</head>
<body>

    <nav class="navbar">

        <div class="logo">
            📦 Gestión de Productos
        </div>

        <div class="nav-links">

            <a href="{{ route('dashboard') }}"
               class="btn-dashboard">
                Dashboard
            </a>

            <a href="{{ route('products.index') }}"
               class="btn-inventario">
                Inventario
            </a>

            <a href="{{ route('products.create') }}"
               class="btn-nuevo">
                + Nuevo Producto
            </a>

        </div>

    </nav>

    <div class="container">

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>

</body>
</html>