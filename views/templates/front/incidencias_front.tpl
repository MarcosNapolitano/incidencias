{extends file='page.tpl'}
{block name='page_content'}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-edit"></i>
        {l s='Crear Registro' mod='incidencias'}
    </div>
    
    <div class="panel-body">
        <form action="" method="post" class="form-horizontal">
            
            {* Campo de texto *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Título' mod='incidencias'}
                    <span class="required">*</span>
                </label>
                <div class="col-lg-9">
                    <input type="text" 
                           name="titulo" 
                           class="form-control" 
                           value="{if isset($registro.titulo)}{$registro.titulo}{/if}"
                           required />
                </div>
            </div>
            
            {* Textarea *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Descripción' mod='incidencias'}
                </label>
                <div class="col-lg-9">
                    <textarea name="descripcion" 
                              class="form-control" 
                              rows="5">{if isset($registro.descripcion)}{$registro.descripcion}{/if}</textarea>
                </div>
            </div>
            
            {* Checkbox *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Activo' mod='incidencias'}
                </label>
                <div class="col-lg-9">
                    <label class="switch">
                        <input type="checkbox" 
                               name="activo" 
                               value="1"
                               {if isset($registro.activo) && $registro.activo}checked{/if} />
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            
            {* Select *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Categoría' mod='incidencias'}
                </label>
                <div class="col-lg-9">
                    <select name="id_categoria" class="form-control">
                        <option value="">-- {l s='Seleccione' mod='incidencias'} --</option>
                        {foreach from=$categorias item=cat}
                            <option value="{$cat.id_categoria}"
                                    {if isset($registro.id_categoria) && $registro.id_categoria == $cat.id_categoria}selected{/if}>
                                {$cat.nombre}
                            </option>
                        {/foreach}
                    </select>
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
