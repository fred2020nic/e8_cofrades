<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<style>
	.img-logo {
		width: 3em;
		height: 3em;
		object-fit: scale-down;
		object-position: center center;
	}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Provincia</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Crear Provincia</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid">
				<table class="table table-bordered table-stripped">
					<colgroup>
						<col width="15%">
						<col width="20%">
						<col width="15%">
						<col width="20%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Fomento</th>
							<th>Logo</th>
							<th>Provincia</th>
							<th>Usuario</th>
							<th>Cod Postal</th>
							<th>Stado</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT * from `provincia` where delete_flag= 0 order by `des_provincia` asc ");
						while ($row = $qry->fetch_assoc()) :
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
								<td class="text-center">
									<img src="<?= validate_image($row['image_path']) ?>" alt="Brand Logo - <?= $row['name'] ?>" class="img-logo img-thumbnail">
								</td>
								<td><?php echo $row['des_provincia'] ?></td>
								<td><?php echo $row['usuario'] ?></td>
								<td><?php echo $row['cod_postal'] ?></td>
								<td class="text-center">
									<?php if ($row['status'] == 1) : ?>
										<span class="badge badge-success mx-3 rounded-pill">Activo</span>
									<?php else : ?>
										<span class="badge badge-danger mx-3 rounded-pill">Inactivo</span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										Acción
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			_conf("¿Estás seguro de eliminar esta marca de forma permanente?", "delete_brand", [$(this).attr('data-id')])
		})
		$('#create_new').click(function() {
			uni_modal("Agregar Provincia", "cofrades_aux/manage_provincia.php", "mid-large")
		})
		$('.edit_data').click(function() {
			uni_modal("Actualizar Provicincia", "cofrades_aux/manage_provincia.php?id=" + $(this).attr('data-id'), "mid-large")
		})
		$('.table th, .table td').addClass("align-middle px-2 py-1")
		$('.table').dataTable();
	})

	function delete_brand($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_provincia",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("Ocurrió un error", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("Ocurrió un error", 'error');
					end_loader();
				}
			}
		})
	}
</script>