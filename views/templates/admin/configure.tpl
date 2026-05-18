<h2>Configuración de Incidencias</h2>

{if $success}
<div class="alert alert-success">
    {l s='Actualización Correcta' mod='incidencias'}
</div>
{/if}

{if $error}
<div class="alert alert-danger">
    {l s='Ha ocurrido un error al actualizar' mod='incidencias'}
</div>
{/if}

<!-- TIPOS to do: agregar escapes -->
<div class="card">
  <div class="card-header">
    <h3 class="modal-title">Tipos de Incidencias</h3>
  </div>

  <div class="card-body">
    <table class="table">
      <thead class="thead-default">
        <tr>
          <th><input type="checkbox"></th>
          <th>ID</th>
          <th>Nombre</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$tipos item=$tipo}
        <tr>
          <td><input type="checkbox"></td>
          <td>{$tipo.info.id_tipo}</td>
          <td>{$tipo.info.tipo}</td>
          <td>
            <div class="btn-group btn-group-sm">
              <button 
                class="btn btn-primary btn-editar-tipo" 
                data-toggle="modal" 
                data-target="#modalTipo"
                data-id="{$tipo.info.id_tipo}"
                data-nombre="{$tipo.info.tipo}"
                data-mensaje="{$tipo.info.mensaje_predefinido}"
                data-aplica='{$tiposJson|escape:'htmlall':'UTF-8'}'
                />
                <i class="material-icons">edit</i> Editar
              </button>
            </div>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>

  <div class="card-footer">
    <button class="btn btn-primary btn-sm ml-auto btn-new-tipo" data-toggle="modal" data-target="#modalTipo">
      <i class="material-icons">add</i> Nuevo Tipo
    </button>
    <button class="btn btn-danger btn-sm">
      <i class="material-icons" style="vertical-align: middle">delete</i> Borrar seleccionados
    </button>
  </div>
</div>

<!-- MODAL TIPOS -->
<div class="modal fade" id="modalTipo" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Nuevo Tipo de Incidencia</h5>
      </div>

      <div class="modal-body">
        <form method="POST" action="{$smarty.server.REQUEST_URI}" id="formTipo">
          <input type="hidden" name="saveTipo" value="1">
          <input type="hidden" name="id_tipo" value="">
          <div class="form-group">
            <label class="form-control-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>
          <div class="form-group">
            <label class="form-control-label">Aplica a</label>
            <div class="list-group">
              {foreach from=$estados item=$estado}
              <label class="list-group-item">
                <input type="checkbox" name="aplica[]" value="{$estado.id_order_state}">{$estado.name}</input>
              </label>
              {/foreach}
            </div>
          </div>
          <div class="form-group">
            <label class="form-control-label">Mensaje Predefinido</label>
            <div class="list-group">
              <label class="list-group-item">
                <textarea name="mensaje" class="mr-2"></textarea>
              </label>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="formTipo" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Categorias -->
<div class="card">
  <div class="card-header">
    <h3 class="modal-title">Categorías</h3>
  </div>

  <div class="card-body">
    <table class="table">
      <thead class="thead-default">
        <tr>
          <th><input type="checkbox"></th>
          <th>ID</th>
          <th>Nombre</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$categorias item=$categoria}
        <tr>
          <td><input type="checkbox"></td>
          <td>{$categoria.id_categoria}</td>
          <td>{$categoria.categoria}</td>
          <td>
            <div class="btn-group btn-group-sm">
              <button 
                class="btn btn-primary btn-editar-categoria" 
                data-toggle="modal" 
                data-target="#modalCategoria"
                data-id="{$categoria.id_categoria}"
                data-nombre="{$categoria.categoria}">
                <i class="material-icons">edit</i> Editar
              </button>
            </div>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>

  <div class="card-footer">
    <button class="btn btn-primary btn-sm ml-auto btn-new-categoria" data-toggle="modal" data-target="#modalCategoria">
      <i class="material-icons">add</i> Nueva categoría
    </button>
    <button class="btn btn-danger btn-sm">
      <i class="material-icons" style="vertical-align: middle">delete</i> Borrar seleccionados
    </button>
  </div>
</div>

<!-- MODAL CATEGORIAS -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Nueva categoría</h5>
      </div>

      <div class="modal-body">
        <form method="POST" action="{$smarty.server.REQUEST_URI}" id="formCategoria">
          <input type="hidden" name="saveCategoria" value="1">
          <input type="hidden" name="id_categoria" value="">
          <div class="form-group">
            <label class="form-control-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="formCategoria" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>

<!-- ENCARGADOS -->
<div class="card">
  <div class="card-header">
    <h3 class="modal-title">Empleados</h3>
  </div>

  <div class="card-body">
    <table class="table">
      <thead class="thead-default">
        <tr>
          <th><input type="checkbox"></th>
          <th>ID</th>
          <th>Nombre</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$encargados item=$encargado}
        <tr>
          <td><input type="checkbox"></td>
          <td>{$encargado.id_encargado}</td>
          <td>{$encargado.encargado}</td>
          <td>
            <div class="btn-group btn-group-sm">
              <button 
                class="btn btn-primary btn-editar-encargado" 
                data-toggle="modal" 
                data-target="#modalEncargado"
                data-id="{$encargado.id_encargado}"
                data-nombre="{$encargado.encargado}"
                />
                <i class="material-icons">edit</i> Editar
              </button>
            </div>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>

  <div class="card-footer">
    <button class="btn btn-primary btn-sm ml-auto btn-new-encargado" data-toggle="modal" data-target="#modalEncargado">
      <i class="material-icons">add</i> Nuevo Empleado
    </button>
    <button class="btn btn-danger btn-sm">
      <i class="material-icons" style="vertical-align: middle">delete</i> Borrar seleccionados
    </button>
  </div>
</div>

<!-- MODAL ENCARGADOS -->
<div class="modal fade" id="modalEncargado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Nueva categoría</h5>
        <button type="button" class="close" data-dismiss="modalEncargado">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="POST" action="{$smarty.server.REQUEST_URI}" id="formEncargado">
          <input type="hidden" name="saveEncargado" value="1">
          <input type="hidden" name="id_encargado" value="">
          <div class="form-group">
            <label class="form-control-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="formEncargado" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
