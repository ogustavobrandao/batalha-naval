@php
    $isProa  = $parte === 0;
    $isPopa  = $parte === $tamanho - 1;

    $rotacao = match((int)$direcao) {
        0 => 0,
        1 => 90,
        2 => 180,
        3 => 270,
        default => 0,
    };

    $opacity = $status === 'afundado' ? '0.35' : '1';
    $filtro  = $status === 'acerto'   ? 'drop-shadow(0 0 6px #ef4444)' : 'none';
@endphp

@php
    $isProa = $parte === 0;
    $isPopa = $parte === $tamanho - 1;

    $rotacao = match((int)$direcao) {
        0 => 0,
        1 => 90,
        2 => 180,
        3 => 270,
        default => 0,
    };

    $opacity = $status === 'afundado' ? '0.4' : '1';
    $filtro  = $status === 'acerto' ? 'drop-shadow(0 0 4px #ef4444) brightness(1.4)' : 'brightness(1.3)';
@endphp

<div class="absolute inset-0 flex items-center justify-center pointer-events-none"
     style="transform: rotate({{ $rotacao }}deg); opacity: {{ $opacity }}; filter: {{ $filtro }}; z-index: 999;">

    @if($tipo === 'submarino')
        {{-- Submarino: 1 bloco, desenho completo --}}
        <svg viewBox="0 0 40 40" class="w-[90%] h-[90%]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="20" cy="25" rx="15" ry="8" fill="rgba(255,255,255,0.25)"/>
            <rect x="15" y="13" width="10" height="11" rx="3" fill="rgba(255,255,255,0.3)"/>
            <ellipse cx="20" cy="25" rx="15" ry="8" stroke="white" stroke-opacity="0.5" stroke-width="1.5"/>
            <line x1="20" y1="5" x2="20" y2="13" stroke="white" stroke-opacity="0.7" stroke-width="2"/>
            <circle cx="20" cy="5" r="2.5" fill="white" opacity="0.7"/>
            <circle cx="12" cy="25" r="2" fill="white" opacity="0.4"/>
            <circle cx="20" cy="27" r="2" fill="white" opacity="0.4"/>
            <circle cx="28" cy="25" r="2" fill="white" opacity="0.4"/>
        </svg>

    @elseif($isProa)
        {{-- PROA: ponta da frente --}}
        <svg viewBox="0 0 40 40" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 13 L28 13 Q40 20 28 27 L2 27 Z" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
            <line x1="2" y1="20" x2="28" y2="20" stroke="white" stroke-opacity="0.3" stroke-width="0.8"/>
            <circle cx="20" cy="20" r="3" fill="white" opacity="0.6"/>
            <circle cx="10" cy="20" r="2" fill="white" opacity="0.35"/>
        </svg>

    @elseif($isPopa)
        {{-- POPA: traseira com chaminés --}}
        <svg viewBox="0 0 40 40" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="13" width="38" height="14" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
            <rect x="8" y="5" width="7" height="10" rx="1.5" fill="rgba(255,255,255,0.35)" stroke="rgba(255,255,255,0.5)" stroke-width="0.8"/>
            <rect x="20" y="3" width="5" height="12" rx="1.5" fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="0.8"/>
            <line x1="0" y1="20" x2="38" y2="20" stroke="white" stroke-opacity="0.3" stroke-width="0.8"/>
        </svg>

    @else
        {{-- MEIO: estrutura central --}}
        <svg viewBox="0 0 40 40" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="13" width="40" height="14" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
            <rect x="6" y="6" width="6" height="9" rx="1" fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="0.8"/>
            <rect x="28" y="7" width="6" height="8" rx="1" fill="rgba(255,255,255,0.25)" stroke="rgba(255,255,255,0.4)" stroke-width="0.8"/>
            <line x1="16" y1="13" x2="16" y2="3" stroke="white" stroke-opacity="0.5" stroke-width="1.5"/>
            <circle cx="16" cy="3" r="2" fill="white" opacity="0.6"/>
            <line x1="0" y1="20" x2="40" y2="20" stroke="white" stroke-opacity="0.25" stroke-width="0.8"/>
        </svg>
    @endif
</div>
