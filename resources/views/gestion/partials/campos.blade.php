{{--
    Campos de precio y stock de un producto en el puesto.
    Se usa en el formulario de añadir y en el de editar.
    Necesita: $valores (el pivot del producto al editar, o null al añadir)
--}}
<div class="field">
    <label for="stock">Stock</label>
    <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $valores->stock ?? 0) }}">
</div>
<div class="field">
    <label for="precio_b2c">Precio particular (€)</label>
    <input id="precio_b2c" name="precio_b2c" type="number" step="0.01" min="0" value="{{ old('precio_b2c', $valores->precio_b2c ?? '') }}">
</div>
<div class="field">
    <label for="precio_b2b">Precio empresa (€)</label>
    <input id="precio_b2b" name="precio_b2b" type="number" step="0.01" min="0" value="{{ old('precio_b2b', $valores->precio_b2b ?? '') }}">
</div>
<div class="field">
    <label for="minimo_b2b">Mínimo para empresas</label>
    <input id="minimo_b2b" name="minimo_b2b" type="number" min="1" value="{{ old('minimo_b2b', $valores->minimo_b2b ?? 1) }}">
</div>
<div class="field">
    <label for="descuento_pct">Descuento (%)</label>
    <input id="descuento_pct" name="descuento_pct" type="number" min="0" max="100" value="{{ old('descuento_pct', $valores->descuento_pct ?? 0) }}">
</div>
