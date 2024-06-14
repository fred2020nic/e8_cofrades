<?php
require_once('./../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `localidad` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal img#cimg {
        height: 5em;
        width: 5em;
        object-fit: scale-down;
        object-position: center center;
    }
</style>
<div class="container-fluid">
    <form action="" id="brand-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="name" class="control-label">Nombre Localidad</label>
            <input name="name" id="name" class="form-control form-control-sm" value="<?php echo isset($name) ? $name : ''; ?>" required />
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
            <label for="usuario_mod" class="control-label">Usuario Creador</label>
            <?php
            $value = ''; // Default value
            $user = $_settings->userdata('username');

            if (isset($usuario)) {
                $value = $usuario;
            } elseif (isset($usuario_mod)) {
                $value = $usuario_mod;
            } else {  // Corrected elseif condition
                $value = isset($user) ? $user : ''; // Set $value to $user if it exists, otherwise empty string
            }
            ?>
            <input type="text" name="usuario" id="usuario" class="form-control form-control-sm" placeholder="Enter Username" value="<?php echo $value; ?>" readonly />
        </div>
        <div class="form-group">
            <label for="usuario" class="control-label">Fecha Creada</label>

            <?php
            date_default_timezone_set('America/Costa_Rica'); // Establecemos la zona horaria a Costa Rica

            // Verificamos si $date_created está definido y no es nulo
            if (!isset($date_created) || is_null($date_created)) {
                $value = date("Y-m-d H:i:s"); // Si no está definido, usamos la fecha y hora actual
            } else {
                $value = $date_created; // Si está definido, usamos su valor
            }
            ?>
            <input type="text" name="date_created" id="date_created" class="form-control form-control-sm" value="<?php echo $value; ?>" readonly />
        </div>

        <div class="form-group">
            <label for="usuario" class="control-label">Usuario Modificador</label>
            <input name="usuario_mod" id="usuario_mod" class="form-control form-control-sm" value="<?php echo isset($usuario) ? ($_settings->userdata('username')) : ($_settings->userdata("username")) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="usuario" class="control-label">Fecha Modificador</label>

            <?php
            $value = '';

            // Get current time in CST timezone
            date_default_timezone_set('America/Costa_Rica'); // Set timezone to CST
            $date_mod = " ";

            // Set value for the input field
            if (!isset($date_mod)) {
                $value = $date_mod;
            } else {
                $date_mod = date("Y-m-d H:i:s");
                $value =  $date_mod;
            }
            ?>
            <input type="text" name="date_mod" id="date_mod" class="form-control form-control-sm" value="<?php echo $value; ?>" readonly />
        </div>


  <div class="form-group">
            <label for="status" class="control-label">Estado</label>
            <select name="status" id="status" class="custom-select selevt">
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
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
        $('#brand-form').submit(function(e) {
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
                        location.href = "./?page=cofrades_aux/localida";
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
        document.getElementById('cod_bac').addEventListener('input', function(e) {
        if (this.value.length > 4) {
            this.value = this.value.slice(0,4); 
        }
    });

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