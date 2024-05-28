<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `localidad` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = stripslashes($v);
		}
	}
}
?>
<div class="card card-outline card-info rounded-0">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Actualizar " : "Crear " ?> Localidad</h3>
	</div>
	<div class="card-body">
		<form action="" id="product-form">

			<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

			<div class="form-group">
				<label for="name" class="control-label">Nombre</label>
				<input name="name" id="name" type="text" class="form-control rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required>
			</div>

			<div class="form-group">
				<label for="description" class="control-label">Descripción</label>
				<textarea name="description" id="description" type="text" class="form-control rounded-0 summernote" required><?php echo isset($description) ? $description : ''; ?></textarea>
			</div>
			
			<div class="form-group">
				<label for="provincia_id" class="control-label">Provincia</label>
				<select name="provincia_id" id="provincia_id" class="custom-select select2">
					<option value="" <?= !isset($provincia_id) ? "selected" : "" ?> disabled></option>
					<?php
					$brands = $conn->query("SELECT * FROM provincia where delete_flag = 0 " . (isset($provincia_id) ? " or id = '{$provincia_id}'" : "") . " order by `des_provincia` asc ");
					while ($row = $brands->fetch_assoc()) :
					?>
						<option value="<?= $row['id'] ?>" <?= isset($provincia_id) && $provincia_id == $row['id'] ? "selected" : "" ?>><?= $row['des_provincia'] ?> <?= $row['delete_flag'] == 1 ? "<small>Eliminado</small>" : "" ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			
			
			
			
			
			<div class="form-group">
				<label for="status" class="control-label">Estado</label>
				<select name="status" id="status" class="custom-select selevt">
					<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
					<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
				</select>
			</div>
			<div class="form-group">
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
			</div>

		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="product-form">Guardar</button>
		<a class="btn btn-flat btn-default" href="?page=products">Cancelar</a>
	</div>
</div>
<script>
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
				url: _base_url_ + "classes/Master.php?f=save_localidad",
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
						// location.href = "./?page=products/view_product&id=" + resp.id;
						location.href = "./?page=cofrades_aux/localidad";
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