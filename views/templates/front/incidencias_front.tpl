{extends file='page.tpl'}
{block name='page_content'}
<div class="panel">
    <div class="panel-heading h2 mb-1">
        <i class="icon-edit"></i>
        {l s='Incidencias Actuales' mod='incidencias'}
    </div>

    {if $success == 1}
    <div class="alert alert-success">
        {l s='Incidencia creada correctamente' mod='incidencias'}
    </div>
    {/if}

    {if $error == 1}
    <div class="alert alert-danger">
        {l s='Ha ocurrido un error al crear la incidencia' mod='incidencias'}
    </div>
    {/if}
    
    <div class="panel-body">
    {if !empty($incidencias)}
      <table class="table table-striped table-hover" id="resultados">
          <thead>
              <tr>
                  <th>{l s='Fecha' mod='incidencias'}</th>
                  <th>{l s='Pedido' mod='incidencias'}</th>
                  <th>{l s='Tipo' mod='incidencias'}</th>
                  <th style="text-align: center">{l s='Estado' mod='incidencias'}</th>
              </tr>
          </thead>

          <tbody>
              {foreach from=$incidencias item=incidencia}
                  <tr onclick="window.location='{$link->getModuleLink('incidencias','menu',
                  ['id'=>$incidencia.id_incidencia])|escape:'html':'UTF-8'}'"
                  style="cursor:pointer;">

                      <td>{$incidencia.creado|date_format:"%d/%m/%Y"}</td>
                      <td class="{if $incidencia.mensaje_employee}nuevo-mensaje{/if}">{$incidencia.reference|escape:'html':'UTF-8'}</td>
                      <td>{$incidencia.tipo|escape:'html':'UTF-8'}</td>
                      <td class="{if $incidencia.estado}bg-danger{else}bg-success{/if} text-white" style="text-align: center">
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
<div class="panel">
    <div class="panel-heading h4">
        <i class="icon-edit"></i>
        {l s='Crear Incidencia' mod='incidencias'}
    </div>
    
    <div class="panel-body">
        <form action="" method="post" class="form-horizontal">
            
            {* Campo de texto *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Pedido' mod='incidencias'}
                </label>
                <div class="col-lg-9">
                    <select name="id_order" class="form-control" required>
                        <option value="">-- {l s='Seleccione' mod='incidencias'} --</option>
                        {foreach from=$pedidos item=order}
                            <option value="{$order.id_order}"
                                    {if isset($registro.id_categoria) && $registro.id_categoria == $cat.id_categoria}selected{/if}>
                                {$order.reference} - {$order.date_add|date_format:"%d/%m/%y"}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            
            {* Select *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Motivo' mod='incidencias'}
                </label>
                <div class="col-lg-9">
                    <select name="id_tipo" class="form-control" required>
                        <option value="">-- {l s='Seleccione' mod='incidencias'} --</option>
                        {foreach from=$tipos key=id item=tipo}
                            <option value="{$tipo.id_tipo}"
                                    {if isset($registro.id_categoria) && $registro.id_categoria == $cat.id_categoria}selected{/if}>
                                {$tipo.tipo}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>

            {* Textarea *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Descripción' mod='incidencias'}
                </label>
                <div class="col-lg-9">
                    <textarea name="mensaje" 
                              class="form-control" 
                              rows="5"
                              placeholder="Indique su incidencia..."
                              style="resize: none"
                              required></textarea>
                    </div>
            </div>
            
            {* Botones *}
            <div class="panel-footer">
                <button type="submit" 
                        name="submitGuardar" 
                        class="btn btn-default pull-right">
                    <i class="process-icon-save"></i>
                    {l s='Guardar' mod='incidencias'}
                </button>
                
                <a href="{$link->getAdminLink('AdminHolaMundo')}" 
                   class="btn btn-default">
                    <i class="process-icon-cancel"></i>
                    {l s='Cancelar' mod='incidencias'}
                </a>
            </div>
            
        </form>
    </div>
</div>
{/block}
