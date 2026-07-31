@extends('invoices.layout')

@section('title', 'Ver Factura')

@section('content')
<div style="background: rgba(255,255,255,.90); backdrop-filter: blur(12px); border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,.06);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px;">
        <div>
            <h1 style="color: #1e293b; font-size: 28px; margin: 0;">Factura {{ $factura->numero_factura }}</h1>
            <p style="color: #64748b; margin-top: 5px;">Detalles completos del comprobante</p>
        </div>
        <a href="{{ route('facturas.index') }}" style="background: #e2e8f0; color: #475569; padding: 10px 20px; border-radius: 12px; font-weight: bold; text-decoration: none;">&larr; Volver</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
            <h3 style="color: #475569; font-size: 14px; margin: 0 0 10px 0; text-transform: uppercase;">Información General</h3>
            <p style="margin: 5px 0;"><strong>Cliente:</strong> {{ $factura->cliente?->name ?? 'Sin cliente' }}</p>
            <p style="margin: 5px 0;"><strong>Fecha de Emisión:</strong> {{ $factura->fecha_emision->format('d/m/Y') }}</p>
            <p style="margin: 5px 0;">
                <strong>Estado:</strong> 
                @if($factura->estado === 'emitida')
                    <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: bold;">Emitida</span>
                @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: bold;">Anulada</span>
                @endif
            </p>
        </div>
        
        <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
            <h3 style="color: #475569; font-size: 14px; margin: 0 0 10px 0; text-transform: uppercase;">Totales</h3>
            <p style="margin: 5px 0;"><strong>Subtotal:</strong> ${{ number_format($factura->subtotal, 0, ',', '.') }}</p>
            <p style="margin: 5px 0; font-size: 18px; color: #0f766e;"><strong>Total:</strong> ${{ number_format($factura->total, 0, ',', '.') }}</p>
        </div>
    </div>

    <h3 style="color: #1e293b; font-size: 20px; margin-bottom: 15px;">Productos</h3>
    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,.03);">
        <thead style="background: #f0fdfa;">
            <tr>
                <th style="padding: 15px; text-align: left; color: #0f766e;">Producto</th>
                <th style="padding: 15px; text-align: center; color: #0f766e;">Cantidad</th>
                <th style="padding: 15px; text-align: right; color: #0f766e;">Precio Unit.</th>
                <th style="padding: 15px; text-align: right; color: #0f766e;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->detalles as $detalle)
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 15px;"><strong>{{ $detalle->producto?->name }}</strong></td>
                <td style="padding: 15px; text-align: center;">{{ $detalle->cantidad }}</td>
                <td style="padding: 15px; text-align: right;">${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                <td style="padding: 15px; text-align: right; color: #059669; font-weight: bold;">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($factura->estado === 'anulada')
    <div style="margin-top: 30px; background: #fee2e2; padding: 20px; border-radius: 16px; border: 1px solid #fca5a5;">
        <h3 style="color: #991b1b; font-size: 16px; margin: 0 0 10px 0;">Factura Anulada</h3>
        <p style="margin: 0; color: #7f1d1d;"><strong>Motivo:</strong> {{ $factura->motivo_anulacion }}</p>
        <p style="margin: 5px 0 0 0; color: #7f1d1d;"><strong>Fecha Anulación:</strong> {{ $factura->fecha_anulacion->format('d/m/Y') }}</p>
        
        @if($factura->notasCredito->count() > 0)
            <p style="margin: 10px 0 0 0; color: #7f1d1d;"><strong>Nota Crédito Generada:</strong> {{ $factura->notasCredito->first()->numero_nota_credito }}</p>
        @endif
    </div>
    @endif
</div>
@endsection
