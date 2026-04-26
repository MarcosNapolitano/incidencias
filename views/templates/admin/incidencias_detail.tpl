<div class="panel">
    <div class="panel-body">
      <div class="incidencia-chat">
          <h3>Incidencia Pedido {$incidencia.reference|escape:'html':'UTF-8'}</h3>
          <table class="table table-striped table-hover" id="resultados">
              <thead>
                  <tr>
                      <th>{l s='Fecha' mod='incidencias'}</th>
                      <th>{l s='Estado' mod='incidencias'}</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>{$incidencia.creado|date_format:"%d/%m/%Y"}</td>
                      <td class="{if $incidencia.estado}abierta{else}cerrada{/if}">
                        {if $incidencia.estado == 1}
                          Abierta
                        {else}
                          Cerrada
                        {/if}
                      </td>
                  </tr>
              </tbody>
          </table>
        {foreach from=$mensajes item=msg}
          {* se compara el id contexto contra el de la bbdd *}
          {assign var=es_cliente value=($msg.id_customer == $id_employee)}
          <div class="mensaje {if $es_cliente}mensaje-cliente{else}mensaje-admin{/if}">
            
            <div class="burbuja">
              <div class="texto">
                {$msg.mensaje|escape:'html':'UTF-8'}
              </div>

              <div class="meta">
                <span class="autor">
                  {if $es_cliente}Soporte{else}Cliente{/if}
                </span>
                <span class="fecha">
                  - Enviado el {$msg.creado|date_format:"%d/%m/%Y"}
                </span>
              </div>
            </div>

          </div>
        {/foreach}
    </div>
  </div>
  <div class="panel-body">
      <form method="post" class="defaultForm" style="display: flex; flex-direction: column">

          <label class="control-label col-lg-3 required" style="text-align: left">Categoría</label>
          <select name="categoria" class="form-control">
            {foreach from=$categorias item=cat}
            <option value="{$cat.id_categoria}" 
            {if $cat.id_categoria == $incidencia.id_categoria}selected{/if}>
            {$cat.categoria}
            </option>
            {/foreach}
          </select>

          <label class="control-label col-lg-3 required" style="text-align: left">Tipo</label>
          <select name="tipo" class="form-control">
            {foreach from=$tipos item=tipo}
            <option value="{$tipo.id_tipo}" {if $tipo.id_tipo == $incidencia.id_tipo}selected{/if}>
            {$tipo.tipo}
            </option>
            {/foreach}
          </select>

          <label class="control-label col-lg-3 required" style="text-align: left">Encargado</label>
          <select name="encargado" class="form-control">
            {foreach from=$encargados item=encargado}
            <option value="{$encargado.id_encargado}" {if $encargado.id_encargado == $incidencia.id_encargado}selected{/if}>
            {$encargado.encargado}
            </option>
            {/foreach}
          </select>

          <label class="control-label col-lg-3" style="text-align: left">Mensaje</label>
          <select name="estado" class="form-control">
            <option value="1" {if $incidencia.estado}selected{/if}>Activo</option>
            <option value="0"{if !$incidencia.estado}selected{/if}>Cerrado</option>
          </select>
          
          <label class="control-label col-lg-3" style="text-align: left">Mensaje</label>
          <textarea name="mensaje" rows="15" class="form-control h-100" style="width: 100; resize: none" placeholder="Escribe tu mensaje..." required></textarea>
          <button type="submit" name="submitMensaje" class="btn btn-primary mt-2">
            Enviar
          </button>
      </form>
      <button onclick="window.location='{$admin_link}'" class="btn btn-primary mt-2">
        Volver
      </button>
  </div>
</div>
