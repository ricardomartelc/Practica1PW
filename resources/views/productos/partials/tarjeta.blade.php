{{-- Tarjeta de un producto. Necesita: $producto --}}
<article class="card">
    <div class="card-body">
        <h3>{{ $producto->nombre }}</h3>
        <p class="origin">{{ $producto->categoria }} · se vende por {{ $producto->unidad }}</p>
        <div class="card-actions">
            <a href="{{ route('productos.show', $producto) }}" class="btn-small">Ver producto</a>
        </div>
    </div>
</article>
