<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `cofrades` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = stripslashes($v);
		}
	}
}

$esEdicion = isset($_GET['id']) && $_GET['id'] > 0;
?>
<div class="card card-outline card-info rounded-0">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Actualizar " : "Crear " ?> Cofrades</h3>
	</div>
	<div class="card-body">
		<form action="" id="product-form">

			<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
			Datos de generales
			<div class="row">
				<div class="form-group col-4">
					<label for="Aspirante" class="control-label">Hermano activo</label>


					<select name="Hermano_activo" id="Hermano_activo" class="form-control rounded-0" required>
						<option value="verdadero" <?php if (isset($Hermano_activo) && $Hermano_activo == 'verdadero') echo 'selected'; ?>>verdadero</option>
						<option value="falso" <?php if (isset($Hermano_activo) && $Hermano_activo == 'falso') echo 'selected'; ?>>falso</option>
					</select>

				</div>

				<div class="form-group col-4">
					<label for="Aspirante" class="control-label">Aspirante</label>


					<select name="Aspirante" id="Aspirante" class="form-control rounded-0" required>
						<option value="verdadero" <?php if (isset($Aspirante) && $Aspirante == 'verdadero') echo 'selected'; ?>>verdadero</option>
						<option value="falso" <?php if (isset($Aspirante) && $Aspirante == 'falso') echo 'selected'; ?>>falso</option>
					</select>

				</div>

				<div class="form-group  col-4">
					<label for="baja_id" class="control-label">Motivo de Baja</label>
					<select name="baja_id" id="baja_id" class="custom-select select2">
						<option value="" <?= !isset($baja_id) ? "selected" : "" ?> disabled></option>
						<?php
						$bajas = $conn->query("SELECT * FROM motivo_baja where delete_flag = 0 " . (isset($baja_id) ? " or id = '{$baja_id}'" : "") . " order by `des_baja` asc ");
						while ($row = $bajas->fetch_assoc()) :
						?>
							<option value="<?= $row['id'] ?>" <?= isset($baja_id) && $baja_id == $row['id'] ? "selected" : "" ?>><?= $row['des_baja'] ?> <?= $row['delete_flag'] == 1 ? "<small>Eliminado</small>" : "" ?></option>
						<?php endwhile; ?>
					</select>
				</div>

			</div>
			<div class="row">
				<div class="form-group col-1">
					<label for="D_d" class="control-label">Da</label>
					<input name="D_d" id="D_d" type="text" class="form-control rounded-0" value="<?php echo isset($D_d) ? $D_d : ''; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Nombre" class="control-label">Nombres</label>
					<input name="Nombre" id="Nombre" type="text" class="form-control " value="<?php echo isset($Nombre) ? $Nombre : ''; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Apellidos" class="control-label">Apellidos</label>
					<input name="Apellidos" id="Apellidos" type="text" class="form-control " value="<?php echo isset($Apellidos) ? $Apellidos : ''; ?>" required>
				</div>

				<div class="form-group col-5">
					<label for="Direccion" class="control-label">Direccion</label>
					<input name="Direccion" id="Direccion" type="text" class="form-control " value="<?php echo isset($Direccion) ? $Direccion : ''; ?>" required>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-1">
					<label for="Codigo_postal" class="control-label">Codigo</label>
					<input name="Codigo_postal" id="Codigo_postal" type="text" class="form-control " value="<?php echo isset($Codigo_postal) ? $Codigo_postal : ''; ?>" required>
				</div>


				
				
	

				<div class="form-group col-3">
					<label for="Pais" class="control-label">Pais</label>
					<input name="Pais" id="Pais" type="text" class="form-control " value="<?php echo isset($Pais) ? $Pais : ''; ?>" required>
				</div>
			

	<div class="form-group col-4">
    <label for="provincia_id" class="control-label">Provincia</label>
    <select name="provincia_id" id="provincia_id" class="custom-select select2" onchange="loadLocalidades(this.value)">
        <option value="" <?= !isset($provincia_id) ? "selected" : "" ?> disabled></option>
        <?php
        $provincias = $conn->query("SELECT * FROM provincia where delete_flag = 0 " . (isset($provincia_id) ? " or id = '{$provincia_id}'" : "") . " order by `des_provincia` asc ");
        while ($row = $provincias->fetch_assoc()) :
        ?>
            <option value="<?= $row['id'] ?>" <?= isset($Provincia_id) && $Provincia_id == $row['id'] ? "selected" : "" ?>><?= $row['des_provincia'] ?> <?= $row['delete_flag'] == 1 ? "<small>Eliminado</small>" : "" ?></option>
        <?php endwhile; ?>
    </select>
</div>

<!-- <div class="form-group col-4">
    <label for="localidad_id" class="control-label">Localidad</label>
    <select name="localidad_id" id="localidad_id" class="custom-select select2">
        <option value="" <?= !isset($localidad_id) ? "selected" : "" ?> disabled></option>
    </select>
</div> -->
<!-- <div class="form-group col-4">
    <label for="localidad_id" class="control-label">Localidad</label>
    <select name="localidad_id" id="localidad_id" class="custom-select select2">
        <option value="" disabled>Seleccione una Localidad</option>
        <?php
        if (isset($provincia_id)) {
            $localidades = $conn->query("SELECT * FROM localidad WHERE provincia_id = $provincia_id AND delete_flag = 0 ORDER BY name ASC");
            while ($row = $localidades->fetch_assoc()) :
        ?>
                <option value="<?= $row['id'] ?>" <?= isset($localidad_id) && $localidad_id == $row['id'] ? "selected" : "" ?>><?= $row['name'] ?></option>
        <?php endwhile;
        }
        ?>
    </select>
</div> -->
<div class="form-group col-4">
    <label for="localidad_id" class="control-label">Localidad</label>
    <select name="localidad_id" id="localidad_id" class="custom-select select2">
        <option value="" disabled selected>Seleccione una Localidad</option>
        <?php
        $localidades = $conn->query("SELECT * FROM localidad WHERE provincia_id = " . (isset($provincia_id) ? $provincia_id : '0') . " AND delete_flag = 0 ORDER BY name ASC");
        while ($row = $localidades->fetch_assoc()) :
        ?>
            <option value="<?= $row['id'] ?>" <?= isset($localidad_id) && $localidad_id == $row['id'] ? "data-selected" : "" ?>><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select>
</div>


</div>
						<!-- location.href = "./?page=cofrades_aux/cofrades"; -->
			Datos de comunicacion
			<div class="row">

				<div class="form-group col-4">
					<label for="Telefono_movil" class="control-label">Telefono movil</label>
					<input name="Telefono_movil" id="Telefono_movil" type="text" class="form-control " value="<?php echo isset($Telefono_movil) ? $Telefono_movil : ''; ?>" required>
				</div>

				<div class="form-group col-4">
					<label for="Telefono_fijo" class="control-label">Telefono fijo</label>
					<input name="Telefono_fijo" id="Telefono_fijo" type="text" class="form-control " value="<?php echo isset($Telefono_fijo) ? $Telefono_fijo : ''; ?>" required>
				</div>

				<div class="form-group col-4">
					<label for="Correo_electronico" class="control-label">Correo electronico</label>
					<input name="Correo_electronico" id="Correo_electronico" type="text" class="form-control " value="<?php echo isset($Correo_electronico) ? $Correo_electronico : ''; ?>" required>
				</div>

			</div>
			Datos de bancarios
			<div class="row">
				<div class="form-group col-4">
					<label for="Importe_cuota" class="control-label">Importe cuota</label>
					<input name="Importe_cuota" id="Importe_cuota" type="number" class="form-control  text-right" value="<?php echo isset($Importe_cuota) ? $Importe_cuota : ""; ?>" required>
				</div>

				<div class="form-group col-4">
					<label for="Dona_hdad" class="control-label">Dona a la hdad</label>
					<input name="Dona_hdad" id="Dona_hdad" type="number" class="form-control text-right" value="<?php echo isset($Dona_hdad) ? $Dona_hdad : ""; ?>" required>
				</div>

				<div class="form-group col-4">
					<label for="Cobro_banco" class="control-label">Cobro banco</label>


					<select name="Cobro_banco" id="Cobro_banco" class="form-control rounded-0" required>
						<option value="verdadero" <?php if (isset($Cobro_banco) && $Cobro_banco == 'verdadero') echo 'selected'; ?>>verdadero</option>
						<option value="falso" <?php if (isset($Cobro_banco) && $Cobro_banco == 'falso') echo 'selected'; ?>>falso</option>
					</select>

				</div>
			</div>
			<div class="row">


				<div class="form-group col-3">
					<label for="banco_id" class="control-label">Banco</label>
					<select name="banco_id" id="banco_id" class="custom-select select2">
						<option value="" <?= !isset($banco_id) ? "selected" : "" ?> disabled></option>
						<?php
						$bancos = $conn->query("SELECT * FROM banco where delete_flag = 0 " . (isset($banco_id) ? " or id = '{$banco_id}'" : "") . " order by `des_banco` asc ");
						while ($row = $bancos->fetch_assoc()) :
						?>
							<option value="<?= $row['id'] ?>" <?= isset($banco_id) && $banco_id == $row['id'] ? "selected" : "" ?>><?= $row['des_banco'] ?> <?= $row['delete_flag'] == 1 ? "<small>Eliminado</small>" : "" ?></option>
						<?php endwhile; ?>
					</select>
				</div>

				<div class="form-group col-3">
					<label for="Cuenta_num_ban" class="control-label">CUENTA NUM (BAN)</label>
					<input name="Cuenta_num_ban" id="Cuenta_num_ban" type="text" class="form-control rounded-0 text-right" value="<?php echo isset($Cuenta_num_ban) ? $Cuenta_num_ban : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Cobro_banco" class="control-label">COBRO POR COBRADOR</label>


					<select name="Cobro_por_cobrador" id="Cobro_por_cobrador" class="form-control rounded-0" required>
						<option value="verdadero" <?php if (isset($Cobro_por_cobrador) && $Cobro_banco == 'verdadero') echo 'selected'; ?>>verdadero</option>
						<option value="falso" <?php if (isset($Cobro_por_cobrador) && $Cobro_por_cobrador == 'falso') echo 'selected'; ?>>falso</option>
					</select>

				</div>

				<div class="form-group col-3">
					<label for="Otra_forma_cobro" class="control-label">OTRA FORMA DE COBRO</label>
					<input name="Otra_forma_cobro" id="Otra_forma_cobro" type="text" class="form-control text-right" value="<?php echo isset($Otra_forma_cobro) ? $Otra_forma_cobro : ""; ?>" required>
				</div>

			</div>
			<br>
			<hr>
			<br>
			Otros Datos
			<div class="row">
				<div class="form-group col-3">
					<label for="Fecha_nacimiento" class="control-label">Fecha nacimiento</label>
					<input name="Fecha_nacimiento" id="Fecha_nacimiento" type="date" class="form-control text-right" value="<?php echo isset($Fecha_nacimiento) ? $Fecha_nacimiento : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Ano_inscripcion" class="control-label">Año inscripcion</label>
					<input name="Ano_inscripcion" id="Ano_inscripcion" type="text" class="form-control  text-right" value="<?php echo isset($Ano_inscripcion) ? $Ano_inscripcion : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Fecha_baja" class="control-label">Fecha baja</label>
					<input name="Fecha_baja" id="Fecha_baja" type="date" class="form-control  text-right" value="<?php echo isset($Fecha_baja) ? $Fecha_baja : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Causa_baja" class="control-label">Causa_baja</label>


					<select name="Causa_baja" id="Causa_baja" class="form-control rounded-0" required>
						<option value="opc_1" <?php if (isset($Causa_baja) && $Causa_baja == 'opc_1') echo 'selected'; ?>>Opcion 1</option>
						<option value="opc_2" <?php if (isset($Causa_baja) && $Causa_baja == 'opc_2') echo 'selected'; ?>>Opcion 2</option>
						<option value="opc_3" <?php if (isset($Causa_baja) && $Causa_baja == 'opc_3') echo 'selected'; ?>>falso</option>
					</select>

				</div>
			</div>

			Datos Parroquiales

			<div class="row">

				<div class="form-group col-3">
					<label for="Procede_ltre_hermandad" class="control-label">PROCEDE DE HERMANDAD</label>


					<select name="Procede_ltre_hermandad" id="Procede_ltre_hermandad" class="form-control " required>
						<option value="verdadero" <?php if (isset($Procede_ltre_hermandad) && $Procede_ltre_hermandad == 'verdadero') echo 'selected'; ?>>verdadero</option>
						<option value="falso" <?php if (isset($Procede_ltre_hermandad) && $Procede_ltre_hermandad == 'falso') echo 'selected'; ?>>falso</option>
					</select>

				</div>

				<div class="form-group col-3">
					<label for="Procede_corte_honor" class="control-label">PROCEDE DE CORTE HONOR</label>


					<select name="Procede_corte_honor" id="Procede_corte_honor" class="form-control " required>
						<option value="verdadero" <?php if (isset($Procede_corte_honor) && $Procede_corte_honor == 'verdadero') echo 'selected'; ?>>verdadero</option>
						<option value="falso" <?php if (isset($Procede_corte_honor) && $Procede_corte_honor == 'falso') echo 'selected'; ?>>falso</option>
					</select>

				</div>

				<div class="form-group col-3">
					<label for="Padrino_madrina_1" class="control-label">Padrino/madrina 1</label>
					<input name="Padrino_madrina_1" id="Padrino_madrina_1" type="text" class="form-control  text-right" value="<?php echo isset($Padrino_madrina_1) ? $Padrino_madrina_1 : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Padrino_madrina_2" class="control-label">Padrino/madrina 2</label>
					<input name="Padrino_madrina_2" id="Padrino_madrina_2" type="text" class="form-control  text-right" value="<?php echo isset($Padrino_madrina_2) ? $Padrino_madrina_2 : ""; ?>" required>
				</div>


			</div>

			<div class="row">
				<div class="form-group col-3">
					<label for="Parroquia_bautismo" class="control-label">Parroquia bautismo</label>
					<input name="Parroquia_bautismo" id="Parroquia_bautismo" type="text" class="form-control  text-right" value="<?php echo isset($Parroquia_bautismo) ? $Parroquia_bautismo : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Localidad_parroquia_bautismo" class="control-label">Localidad parroquia</label>
					<input name="Localidad_parroquia_bautismo" id="Localidad_parroquia_bautismo" type="text" class="form-control  text-right" value="<?php echo isset($Localidad_parroquia_bautismo) ? $Localidad_parroquia_bautismo : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Diocesis_parroquia_bautismo" class="control-label">Diocesisparroquia</label>
					<input name="Diocesis_parroquia_bautismo" id="Diocesis_parroquia_bautismo" type="text" class="form-control  text-right" value="<?php echo isset($Diocesis_parroquia_bautismo) ? $Diocesis_parroquia_bautismo : ""; ?>" required>
				</div>

				<div class="form-group col-3">
					<label for="Libro_folio" class="control-label">Libro folio</label>
					<input name="Libro_folio" id="Libro_folio" type="text" class="form-control  text-right" value="<?php echo isset($Libro_folio) ? $Libro_folio : ""; ?>" required>
				</div>


			</div>

			<div class="form-group">
				<label for="Observaciones" class="control-label">Observaciones</label>
				<textarea name="Observaciones" id="Observaciones" type="text" class="form-control  " required><?php echo isset($Observaciones) ? $Observaciones : ''; ?></textarea>
			</div>


			<!-- <div class="form-group">
				<label for="description" class="control-label">Descripción</label>
				<textarea name="description" id="description" type="text" class="form-control  summernote"><?php echo isset($description) ? $description : ''; ?></textarea>
			</div> -->


			<div class="form-group">
				<label for="status" class="control-label">Estado</label>
				<select name="status" id="status" class="custom-select selevt">
					<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
					<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
				</select>
			</div>
			<!-- <div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label for="" class="control-label">Imagen de Localidad</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
							<label class="custom-file-label" for="customFile">Examinar</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="d-flex justify-content-center">
							<img src="<?php echo validate_image(isset($image_path) ? $image_path : "") ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
						</div>
					</div>
				</div>
			</div> -->

		
			


		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="product-form">Guardar</button>
		<a class="btn btn-flat btn-default" href="?page=products">Cancelar</a>
	</div>
</div>
<div class="provincia_seleccionada"></div>




<script>



// 	function loadLocalidades(provinciaId) {
		
//   const provinciaSeleccionadaDiv = document.querySelector('.provincia_seleccionada');

//   if (provinciaId === "") {
//     provinciaSeleccionadaDiv.innerHTML = '';
//   } else {
//     provinciaSeleccionadaDiv.innerHTML = ` ${provinciaId}`; 
//   }
//   window.location.href = "?page=cofrades_aux/manage_cofrades&provincia_id=" + provinciaId; 
// }
function loadLocalidades(provinciaId) {
    const localidadSelect = document.getElementById('localidad_id');
    localidadSelect.innerHTML = '<option value="" disabled selected>Cargando...</option>';

    fetch('http://localhost/0.1_cw/4.e8/e8_cofrades/admin/cofrades_aux/get_localidades.php?provincia_id=' + provinciaId)
        .then(response => {
            // Para depuración, ver el texto recibido
            return response.text().then(text => {
                // console.log('Texto recibido:', text); // Verifica la respuesta recibida
                return JSON.parse(text); // Intenta parsear el JSON
            });
        })
        .then(data => {
            if (Array.isArray(data)) {
                localidadSelect.innerHTML = '<option value="" disabled selected>Seleccione una localidad</option>';
                data.forEach(localidad => {
                    const option = document.createElement('option');
                    option.value = localidad.id;
                    option.textContent = localidad.name;
                    localidadSelect.appendChild(option);
                });
            } else {
                console.error('Datos inesperados:', data);
                localidadSelect.innerHTML = '<option value="" disabled selected>Error al cargar</option>';
            }
        })
        .catch(error => {
            console.error('Error al cargar las localidades:', error);
            localidadSelect.innerHTML = '<option value="" disabled selected>Error al cargar</option>';
        });
}

	window.displayImg = function(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
				_this.siblings('.custom-file-label').html(input.files[0].name)
			}

			reader.readAsDataURL(input.files[0]);
		} else {
			$('#cimg').attr('src', "<?php echo validate_image(isset($image_path) ? $image_path : "") ?>");
			_this.siblings('.custom-file-label').html("Choose file")
		}
	}
	$(document).ready(function() {
		$('.select2').select2({
			width: '100%',
			placeholder: "Selecciona aquí"
		})
		$('#product-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_cofrade",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err => {
					console.log(err)
					alert_toast("Ocurrió un error", 'error');
					end_loader();
				},
				success: function(resp) {
					if (typeof resp == 'object' && resp.status == 'success') {
						location.href = "./?page=cofrades_aux/cofrades";
					} else if (resp.status == 'failed' && !!resp.msg) {
						var el = $('<div>')
						el.addClass("alert alert-danger err-msg").text(resp.msg)
						_this.prepend(el)
						el.show('slow')
						$("html, body").animate({
							scrollTop: _this.closest('.card').offset().top
						}, "fast");
						end_loader()
					} else {
						alert_toast("Ocurrió un error", 'error');
						end_loader();
						console.log(resp)
					}
				}
			})
		})

		$('.summernote').summernote({
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ol', 'ul', 'paragraph', 'height']],
				['table', ['table']],
				['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
			]
		})
	})
</script>