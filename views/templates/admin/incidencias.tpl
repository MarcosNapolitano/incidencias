<div class="panel">
<form method="get" class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>
          <input type="text" name="id"
                 class="form-control"
                 placeholder="ID">
        </th>

        <th>
          <select name="estado" class="form-control">
            <option value="">Estado</option>
            <option value="1">Activo</option>
            <option value="0">Cerrado</option>
          </select>
        </th>

        <th>
          <input type="date" name="fecha" class="form-control">
        </th>

        <th>
          <button class="btn btn-primary">Filtrar</button>
        </th>

        <th>
          <a href="{$link->getModuleLink('incidencias','menu')}">
            Limpiar
          </a>
        </th>
      </tr>
    </thead>
  </table>
</form>
</div>
<div class="panel">
    <div class="panel-body">
    {if !empty($incidencias)}
      <table class="table table-striped table-hover" style="width: 100%" id="resultados">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>{l s='Fecha' mod='incidencias'}</th>
                  <th>{l s='Pedido' mod='incidencias'}</th>
                  <th>{l s='Estado' mod='incidencias'}</th>
              </tr>
          </thead>

          <tbody>
              {foreach from=$incidencias item=incidencia}
                  <tr onclick="window.location='{$admin_link}&id={$incidencia.id_incidencia|escape:'html':'UTF-8'}'"
                  style="cursor:pointer;">

                      <td>{$incidencia.id_incidencia|escape:'html':'UTF-8'}</td>
                      <td>{$incidencia.creado|date_format:"%d/%m/%Y"}</td>
                      <td>{$incidencia.reference|escape:'html':'UTF-8'}</td>
                      <td>
                        {if $incidencia.estado == 1}
                          Abierta
                        {else}
                          Cerrada
                        {/if}
                      </td>
                  </tr>
              {/foreach}
          </tbody>
      </table>
    {else}
      <h3 class="text-center">{l s='No hay incidencias actualmente.' mod='incidencias'}</h3>
    {/if}
    </div>
</div>
