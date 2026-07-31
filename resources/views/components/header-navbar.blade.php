@props(['title', 'navLinks' => [], 'primaryAction' => null, 'primaryActionUrl' => null, 'estadoCaja' => null, 'estadoCajaUrl' => null])

<div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-radius: 24px; padding: 20px 30px; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
    <h1 style="margin: 0; color: #0d3f3c; font-size: 22px; font-weight: 700;">📋 {{ $title }}</h1>
    
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        @foreach($navLinks as $link)
            <a href="{{ $link['url'] }}" style="text-decoration: none; color: #0d3f3c; padding: 10px 18px; border-radius: 12px; font-weight: 600; transition: 0.3s; background: #e6fbf8; display: inline-block;">
                {{ $link['label'] }}
            </a>
        @endforeach
        
        @if($estadoCaja !== null && $estadoCajaUrl !== null)
            @if($estadoCaja)
                <a href="{{ $estadoCajaUrl }}" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 700; transition: 0.3s; background: linear-gradient(135deg, #f59e0b, #fbbf24); display: inline-block;">
                    + Cerrar Caja
                </a>
            @else
                <a href="{{ $estadoCajaUrl }}" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 700; transition: 0.3s; background: linear-gradient(135deg, #14b8a6, #2dd4bf); display: inline-block;">
                    + Abrir Caja
                </a>
            @endif
        @elseif($primaryAction && $primaryActionUrl)
            <a href="{{ $primaryActionUrl }}" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 700; transition: 0.3s; background: linear-gradient(135deg, #14b8a6, #2dd4bf); display: inline-block;">
                + {{ $primaryAction }}
            </a>
        @endif
    </div>
</div>

<style>
    @media (max-width: 768px) {
        [style*="display: flex"][style*="justify-content: space-between"] {
            flex-direction: column;
            align-items: flex-start !important;
        }
    }
</style>
