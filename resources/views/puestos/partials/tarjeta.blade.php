{{-- Tarjeta de un puesto. Necesita: $vendedor --}}
<article class="card">
    <div class="card-body">
        <h3>{{ $vendedor->nombre_puesto }}</h3>
        <p class="origin">{{ $vendedor->ubicacion }}</p>
        <p class="hint">{{ $vendedor->entrega === 'envio' ? 'Envío a domicilio' : 'Recogida en el puesto' }}</p>
        <div class="card-actions">
            <a href="{{ route('puestos.show', $vendedor) }}" class="btn-small">Ver puesto</a>
        </div>
    </div>
</article>
