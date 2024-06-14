<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Cofrades</h3>
		<div class="card-tools">
			<a href="?page=cofrades_aux/manage_cofrades" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Crear Cofrades</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid">
				<table class="table table-bordered table-stripped">
					<colgroup>
						<!-- <col width="5%"> -->
						<!-- <col width="15%"> -->
						<col width="5%">
						<col width="5%">
						<col width="30%">
						<col width="25%">
						<col width="10%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Numero Activo</th>
							<!-- <th>Fecha Creación</th> -->
							<th>Nombres</th>
							<!-- <th>Apellido</th> -->
							<th>Aspirante</th>
							<th>Fecha de Baja</th>
							<th>Mot. Baja</th>
							<!-- <th>Imagen</th> -->
							<th>Estado</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT c.*, CONCAT(c.Nombre, ' ', c.Apellidos) AS nombres, pr.des_provincia AS provincia, l.name AS localidad, b.des_banco AS banco, mb.des_baja AS baja, c.image_path AS image_path FROM `cofrades` c INNER JOIN provincia pr ON c.provincia_id = pr.id INNER JOIN localidad l ON c.localidad_id = l.id INNER JOIN banco b ON c.banco_id = b.id INNER JOIN motivo_baja mb ON c.baja_id = mb.id WHERE c.delete_flag = 0 ORDER BY c.id ASC");
						while ($row = $qry->fetch_assoc()) :
							foreach ($row as $k => $v) {
								$row[$k] = trim(stripslashes($v));
							}
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<!-- <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td> -->
								<td><?php echo ucwords($row['numero_activo']) ?></td>
								
								<td><?php echo ucwords($row['nombres']) ?></td>
								<td><?php echo ucwords($row['Aspirante']) ?></td>
								<td><?php echo ucwords($row['Fecha_baja']) ?></td>
								<td><?php echo ucwords($row['Causa_baja']) ?></td>
								<!-- <td><?php echo ucwords($row['baja']) ?></td>
								<td class="text-center">
									<img src="<?= validate_image($row['image_path']) ?>" alt="Brand Logo - <?= $row['name'] ?>" class="img-logo img-thumbnail">
								</td> -->
								<td class="text-center">
									<?php if ($row['status'] == 1) : ?>
										<span class="badge badge-success px-3 rounded-pill">Activo</span>
									<?php else : ?>
										<span class="badge badge-danger px-3 rounded-pill">Inactivo</span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										Acción
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<!-- <a class="dropdown-item" href="?page=products/view_product&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a> -->
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="?page=cofrades_aux/manage_cofrades&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>

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
			_conf("¿Estás seguro de eliminar este cofrades de forma permanente?", "delete_cofrade", [$(this).attr('data-id')])
		})
		$('.table th, .table td').addClass("align-middle px-2 py-1")
		$('.table').DataTable({
		
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
   
    });
	})

	function delete_localidad($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_cofrade",
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