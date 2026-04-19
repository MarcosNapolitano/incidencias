<div class="panel">
  <div class="panel-heading">
    <i class="icon-smile"></i>
    {l s='Hola Mundo Module' mod='incidencias'}
  </div>

  <div class="panel-body">
    <h2>{$mensaje}</h2>

    <div class="alert alert-info">
      <h4>{l s='Informacion del sistema' mod='incidencias'}</h4>
      <ul>
        <li><strong>{l s='Nombre:' mod='incidencias'}</strong> {$datos.nombre}</li>
        <li><strong>{l s='Version:' mod='incidencias'}</strong> {$datos.version}</li>
        <li><strong>{l s='Fecha Actual:' mod='incidencias'}</strong> {$datos.fecha}</li>
      </ul>
    </div>

    <p class="text-muted">
      {l s='Ejemplo de Modulo con Tab Personalizado' mod='incidencias'}
    </p>
  </div>
</div>
