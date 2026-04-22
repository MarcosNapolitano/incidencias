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
                <td>
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
  <div class="form-group">
    <form method="post" class="defaultForm form-horizontal">
      <div class="form-group">
        <label class="control-label col-lg-3 required" style="text-align: left">Mensaje</label>
        <textarea name="mensaje" class="form-control" style="width: 100%" placeholder="Escribe tu mensaje..." required></textarea>
      </div>
      <button type="submit" name="submitMensaje" class="btn btn-primary mt-2">
        Enviar
      </button>
    </form>
  </div>
</div>
