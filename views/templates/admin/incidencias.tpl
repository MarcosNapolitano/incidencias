<div class="panel">
<form method="post" class="table-responsive">
  <table class="table table-striped table-hover" style="width: 100%">
    <thead>
      <tr>
        <th>
          <label for="id" style="text-align: left; width: auto">ID</label>
        </th>

        <th>
          <label for="fecha" style="text-align: left; width: auto">Fecha</label>
        </th>

        <th>
          <label for="pedido" style="text-align: left; width: auto">Pedido</label>
        </th>

        <th>
          <label for="categoria" style="text-align: left; width: auto">Categoría</label>
        </th>

        <th>
          <label for="tipo" style="text-align: left; width: auto">Tipo</label>
        </th>
        
        <th>
          <label for="estado" style="text-align: left; width: auto">Estado</label>
        </th>

        <th>
          <label for="nuevo" style="text-align: left; width: auto">Nuevo Mensaje</label>
        </th>

        <th>
          <label style="text-align: left; width: auto">Filtrar</label>
        </th>

        <th>
          <label style="text-align: left; width: auto">Reiniciar</label>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <input type="text" name="id">
        </td>

        <td>
          <input type="date" name="fecha" class="form-control">
        </td>

        <td>
          <input type="text" name="pedido" class="form-control" value="{if isset($parametros.filtroPedido)}{$parametros.filtroPedido}{/if}">
        </td>

        <td>
          <select name="categoria" class="form-control">
            <option value="">-</option>
            {foreach from=$categorias item=cat}
            <option value="{$cat.id_categoria}" 
            {if isset($parametros.filtroCategoria) && 
            $parametros.filtroCategoria == $cat.id_categoria}
            selected{/if}>
                    {$cat.categoria}
            </option>
            {/foreach}
          </select>
        </td>

        <td>
          <select name="tipo" class="form-control">
            <option value="">-</option>
            {foreach from=$tipos item=tipo}
            <option value="{$tipo.id_tipo}"
            {if isset($parametros.filtroTipo) && 
            $parametros.filtroTipo == $tipo.id_tipo}
            selected{/if}>
            {$tipo.tipo}
            </option>
            {/foreach}
          </select>
        </td>

        <td>
          <select name="estado" class="form-control">
            <option value="">-</option>
            <option value="1"
            {if isset($parametros.filtroEstado) && 
            $parametros.filtroEstado == "1"}
            selected{/if}>
              Activo
            </option>
            <option value="2"
            {if isset($parametros.filtroEstado) && 
            $parametros.filtroEstado == "2"}
            selected{/if}>
            Cerrado
            </option>
          </select>
        </td>

        <td>
          <input type="checkbox" name="nuevo" 
          {if isset($parametros.filtroNuevo) && $parametros.filtroNuevo=="1"}
          checked{/if}>
        </td>

        <td>
          <button type="submit" name="submitFiltro" class="btn btn-primary">Filtrar</button>
        </td>
        <td>
          <a href="{$admin_link}">Limpiar</a>
        </td>
      </tr>
      <tr class="separator">
        <td colspan="9"></td>
      </tr> 
    {if !empty($incidencias)}
          {foreach from=$incidencias item=incidencia}
            <tr 
            onclick="window.location='{$admin_link}&id={$incidencia.id_incidencia|escape:'html':'UTF-8'}'"
            style="cursor:pointer;">
              <td>{$incidencia.id_incidencia|escape:'html':'UTF-8'}</td>
              <td>{$incidencia.creado|date_format:"%d/%m/%Y"}</td>
              <td class="{if $incidencia.mensaje_customer}nuevo-mensaje{/if}">
                  {$incidencia.reference|escape:'html':'UTF-8'}</td>
              <td class=
                  "{$incidencia.categoria|lower|regex_replace:'/[^a-z0-9]+/':'-'|trim:'-'}">
                  {$incidencia.categoria|escape:'html':'UTF-8'}</td>
              <td>{$incidencia.tipo|escape:'html':'UTF-8'}</td>
              <td class="{if $incidencia.estado}abierta{else}cerrada{/if}">
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
    <a onclick="window.location='{$admin_link}&exportIncidencias=1'" 
       class="btn btn-primary" style="cursor:pointer;">
       Exportar CSV
    </a>
</div>
