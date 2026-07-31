<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->numero_factura }}</title>

    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .ticket {
            width: 80mm;
            padding: 8px;
        }

        .center { text-align: center; }
        .bold { font-weight: bold; }
        .right { text-align: right; }
        .small { font-size: 11px; }
        .total { font-size: 14px; font-weight: bold; }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .btn-print {
            margin: 10px;
            padding: 10px;
            background: #111827;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        @media print {
            .btn-print { display: none; }

            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .ticket {
                width: 80mm;
                padding: 5mm;
            }
        }
    </style>
</head>
<body>

<button class="btn-print" onclick="window.print()">
    Imprimir factura
</button>

<div class="ticket">

    <div class="center">
        @if($company && $company->logo)
            <div><img src="{{ $company->logo_url }}" alt="Logo" style="max-width:60mm; max-height:25mm; object-fit:contain;"></div>
        @endif
        <div class="bold">{{ $company->name ?? 'MI NEGOCIO POS' }}</div>
        @if($company)
            @if($company->nit)<div>NIT: {{ $company->nit }}</div>@endif
            @if($company->address)<div>Dirección: {{ $company->address }}</div>@endif
            @if($company->city)<div>{{ $company->city }}</div>@endif
            @if($company->phone)<div>Tel: {{ $company->phone }}</div>@endif
            @if($company->email)<div>{{ $company->email }}</div>@endif
        @endif
    </div>

    <div class="line"></div>

    <div>
        <div><span class="bold">Factura:</span> {{ $factura->numero_factura }}</div>
        <div><span class="bold">Fecha:</span> {{ $factura->fecha_emision->format('d/m/Y') }}</div>
        <div><span class="bold">Hora:</span> {{ $factura->created_at->format('h:i A') }}</div>
        <div><span class="bold">Cliente:</span> {{ $factura->cliente?->name ?? 'Consumidor final' }}</div>

        @if($factura->cliente?->document)
            <div><span class="bold">Documento:</span> {{ $factura->cliente->document }}</div>
        @endif

        <div><span class="bold">Método:</span> {{ ucfirst($factura->venta?->metodo_pago ?? 'N/A') }}</div>
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <td class="bold">Producto</td>
                <td class="bold right">Subtotal</td>
            </tr>
        </thead>

        <tbody>
            @foreach($factura->detalles as $detalle)
                <tr>
                    <td colspan="2">{{ $detalle->producto?->name }}</td>
                </tr>
                <tr>
                    <td class="small">
                        {{ $detalle->cantidad }} x
                        ${{ number_format($detalle->precio_unitario, 0, ',', '.') }}
                    </td>
                    <td class="right small">
                        ${{ number_format($detalle->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Subtotal:</td>
            <td class="right">${{ number_format($factura->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="total">Total:</td>
            <td class="right total">${{ number_format($factura->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Recibido:</td>
            <td class="right">${{ number_format($factura->venta?->dinero_recibido ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Cambio:</td>
            <td class="right">${{ number_format($factura->venta?->cambio ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center small">
        <div>Gracias por su compra</div>
        <div>Conserve esta factura</div>
    </div>
</div>

<script>
    window.onload = function () {
        // Para abrir impresión automáticamente, descomenta:
        // window.print();
    };
</script>

</body>
</html>
