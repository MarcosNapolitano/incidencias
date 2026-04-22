{extends file='page.tpl'}
{block name='page_content'}
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
    {if $success == 1}
    <div class="alert alert-success">
        {l s='Mensaje enviado correctamente' mod='incidencias'}
    </div>
    {/if}

    {if $error == 1}
    <div class="alert alert-danger">
        {l s='Ha ocurrido un error al enviar el mensaje' mod='incidencias'}
    </div>
    {/if}
  {foreach from=$mensajes item=msg}
    {* se compara el id contexto contra el de la bbdd *}
    {assign var=es_cliente value=($msg.id_customer == $id_customer)}
    <div class="mensaje {if $es_cliente}mensaje-cliente{else}mensaje-admin{/if}">
      
      <div class="burbuja">
        <div class="texto">
          {$msg.mensaje|escape:'html':'UTF-8'}
        </div>

        <div class="meta">
          <span class="autor">
            {if $es_cliente}Tú{else}Soporte{/if}
          </span>
          <span class="fecha">
            - Enviado el {$msg.creado|date_format:"%d/%m/%Y"}
          </span>
        </div>
      </div>

    </div>
  {/foreach}
  <form method="post" class="mt-3">
    <div class="form-group">
      <textarea name="mensaje" class="form-control" rows="3" placeholder="Escribe tu mensaje..." required></textarea>
    </div>
    <button type="submit" name="submitMensaje" class="btn btn-primary mt-2">
      Enviar
    </button>
  </form>
</div>
{/block}
