@extends('products.layout')

@section('title', 'Listado de Productos')

@section('content')

<style>
    .page-container{
        padding:30px;
        min-height:100vh;
        background: linear-gradient(135deg,#e6fffb,#d7f9f4,#b8f4eb);
    }

    .header-card{
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border-radius:24px;
        padding:25px;
        margin-bottom:25px;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .header-card h1{
        margin:0;
        color:#1e293b;
        font-size:32px;
        font-weight:700;
    }

    .header-card p{
        color:#64748b;
        margin-top:8px;
    }

    .table-card{
        background:white;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .table-modern{
        width:100%;
        border-collapse:collapse;
    }

    .table-modern thead{
        background:#e6fffb;
    }

    .table-modern th{
        padding:18px;
        color:#0f766e;
        font-weight:700;
        text-align:left;
        border-bottom:1px solid #ccfbf1;
    }

    .table-modern td{
        padding:16px;
        border-bottom:1px solid #f1f5f9;
    }

    .table-modern tbody tr:hover{
        background:#f2fffd;
        transition:.3s;
    }

    .product-img{
        width:60px;
        height:60px;
        border-radius:12px;
        object-fit:cover;
        border:2px solid #ccfbf1;
    }

    .badge-stock{
        background:#dcfce7;
        color:#166534;
        padding:6px 12px;
        border-radius:20px;
        font-size:12px;
        font-weight:600;
    }

    .actions{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .btn{
        text-decoration:none;
        padding:8px 14px;
        border-radius:10px;
        font-size:13px;
        font-weight:600;
        transition:.3s;
        border:none;
        cursor:pointer;
    }

    .btn:hover{
        transform:translateY(-2px);
    }

    .btn-view{
        background:#dffaf6;
        color:#0f766e;
    }

    .btn-edit{
        background:#e6fffb;
        color:#147a74;
    }

    .btn-delete{
        background:#fee2e2;
        color:#dc2626;
    }

    .empty-card{
        background:white;
        padding:40px;
        border-radius:24px;
        text-align:center;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .empty-card h3{
        color:#475569;
    }

    .empty-card p{
        color:#94a3b8;
    }

    .price{
        font-weight:bold;
        color:#0f766e;
    }
</style>

<div class="page-container">

    <div class="header-card">
        <h1>📦 Inventario de Productos</h1>
        <p>Administra los productos registrados en el sistema.</p>
    </div>

    @if($products->count())

        <div class="table-card">

            <table class="table-modern">

                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($products as $product)

                    <tr>

                        <td>
                            <img
                                src="{{ $product->photo_url }}"
                                alt="{{ $product->name }}"
                                class="product-img">
                        </td>

                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>

                        <td>
                            {{ $product->category }}
                        </td>

                        <td class="price">
                            ${{ number_format($product->price, 0) }}
                        </td>

                        <td>
                            <span class="badge-stock">
                                {{ $product->stock }}
                                {{ $product->unit_of_measurement }}
                            </span>
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('products.show', $product) }}"
                                    class="btn btn-view">
                                    Ver
                                </a>

                                @can('editar-inventario')
                                <a
                                    href="{{ route('products.edit', $product) }}"
                                    class="btn btn-edit">
                                    Editar
                                </a>

                                <form
                                    action="{{ route('products.destroy', $product) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-delete"
                                        onclick="return confirm('¿Eliminar producto?')">
                                        Eliminar
                                    </button>

                                </form>
                                @endcan

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-card">
            <h3>No hay productos registrados</h3>
            <p>Agrega productos para comenzar a gestionar el inventario.</p>
        </div>

    @endif

</div>

@endsection