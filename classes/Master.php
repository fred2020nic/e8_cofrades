<?php
require_once('../config.php');
class Master extends DBConnection
{
	private $settings;
	public function __construct()
	{
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function capture_err()
	{
		if (!$this->conn->error)
			return false;
		else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_category()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'description'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (isset($_POST['description'])) {
			if (!empty($data)) $data .= ",";
			$data .= " `description`='" . addslashes(htmlentities($description)) . "' ";
		}
		$check = $this->conn->query("SELECT * FROM `categories` where `category` = '{$category}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Categoría ya existe";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `categories` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `categories` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$this->settings->set_flashdata('success', "Nueva categoría guardada con éxito.");
			else
				$this->settings->set_flashdata('success', "Categoría actualizada con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_category()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `categories` set delete_flag = 1 where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Categoría eliminada con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_brand()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `brand_list` where `name` = '{$name}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "La marca ya existe.";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `brand_list` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `brand_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			$id = empty($id) ? $this->conn->insert_id : $id;
			if (empty($id))
				$resp['msg'] = "Nueva marca guardada con éxito.";
			else
				$resp['msg'] = "Marca actualizada correctamente.";
			if (!empty($_FILES['img']['tmp_name'])) {
				$ext = $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$dir = base_app . "uploads/brands/";
				if (!is_dir($dir))
					mkdir($dir);
				$name = $id . "." . $ext;
				if (is_file($dir . $name))
					unlink($dir . $name);
				$move = move_uploaded_file($_FILES['img']['tmp_name'], $dir . $name);
				if ($move) {
					$this->conn->query("UPDATE `brand_list` set image_path = CONCAT('uploads/brands/$name','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
				} else {
					$resp['msg'] .= " El logo no se ha podido cargar";
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if (isset($resp['msg']) && $resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}

	function delete_brand()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `brand_list` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Marca eliminada con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

// inicio de salvar banco
	function save_banco()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
				
			}
			// var_dump($data);
		}
		$check = $this->conn->query("SELECT * FROM `banco` where `des_banco` = '{$des_banco}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "El Banco ya existe.";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `banco` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			
			$sql = "UPDATE `banco` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			$id = empty($id) ? $this->conn->insert_id : $id;
			if (empty($id))
				$resp['msg'] = "Nuevo banco guardada con éxito.";
			else
				$resp['msg'] = "Banco actualizado correctamente.";
			if (!empty($_FILES['img']['tmp_name'])) {
				$ext = $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$dir = base_app . "uploads/banco/";
				if (!is_dir($dir))
					mkdir($dir);
				$name = $id . "." . $ext;
				if (is_file($dir . $name))
					unlink($dir . $name);
				$move = move_uploaded_file($_FILES['img']['tmp_name'], $dir . $name);
				if ($move) {
					$this->conn->query("UPDATE `banco` set image_path = CONCAT('uploads/banco/$name','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
				} else {
					$resp['msg'] .= " El logo no se ha podido cargar";
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if (isset($resp['msg']) && $resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}

	function delete_banco()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `banco` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Banco eliminado con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// fin de salvar banco

	// inicio de salvar provncia
	function save_provincia()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
				
			}
			// var_dump($data);
		}
		$check = $this->conn->query("SELECT * FROM `provincia` where `des_provincia` = '{$des_provincia}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "La provincia ya existe.";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `provincia` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `provincia` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			$id = empty($id) ? $this->conn->insert_id : $id;
			if (empty($id))
				$resp['msg'] = "Nueva provincia guardada con éxito.";
			else
				$resp['msg'] = "Provincia actualizada correctamente.";
			if (!empty($_FILES['img']['tmp_name'])) {
				$ext = $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$dir = base_app . "uploads/provincia/";
				if (!is_dir($dir))
					mkdir($dir);
				$name = $id . "." . $ext;
				if (is_file($dir . $name))
					unlink($dir . $name);
				$move = move_uploaded_file($_FILES['img']['tmp_name'], $dir . $name);
				if ($move) {
					$this->conn->query("UPDATE `provincia` set image_path = CONCAT('uploads/provincia/$name','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
				} else {
					$resp['msg'] .= " El logo no se ha podido cargar";
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if (isset($resp['msg']) && $resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}

	function delete_provincia()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `provincia` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Provincia eliminada con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// fin de salvar provincia

	// inicio de salvar baja
	function save_baja()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
				
			}
			// var_dump($data);
		}
		$check = $this->conn->query("SELECT * FROM `motivo_baja` where `des_baja` = '{$des_baja}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "El motivo de baja ya existe.";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `motivo_baja` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `motivo_baja` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			$id = empty($id) ? $this->conn->insert_id : $id;
			if (empty($id))
				$resp['msg'] = "Nuevo Motivo de baja guardado con éxito.";
			else
				$resp['msg'] = "Motivo de baja actualizado correctamente.";
			if (!empty($_FILES['img']['tmp_name'])) {
				$ext = $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$dir = base_app . "uploads/baja/";
				if (!is_dir($dir))
					mkdir($dir);
				$name = $id . "." . $ext;
				if (is_file($dir . $name))
					unlink($dir . $name);
				$move = move_uploaded_file($_FILES['img']['tmp_name'], $dir . $name);
				if ($move) {
					$this->conn->query("UPDATE `motivo_baja` set image_path = CONCAT('uploads/baja/$name','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
				} else {
					$resp['msg'] .= " El logo no se ha podido cargar";
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if (isset($resp['msg']) && $resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}

	function delete_baja()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `motivo_baja` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Motivo de baja eliminada con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// fin de salvar Motivo de baja




	function save_product()
	{
		$_POST['description'] = htmlentities($_POST['description']);
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `product_list` where `name` = '{$name}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "El producto ya existe";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `product_list` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `product_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			$pid = empty($id) ? $this->conn->insert_id : $id;
			$resp['id'] = $pid;
			if (empty($id))
				$resp['msg'] = "Nuevo producto guardado con éxito";
			else
				$resp['msg'] = "Producto actualizado con éxito";
			if (!empty($_FILES['img']['tmp_name'])) {
				$ext = $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$dir = base_app . "uploads/products/";
				if (!is_dir($dir))
					mkdir($dir);
				$name = $pid . "." . $ext;
				if (is_file($dir . $name))
					unlink($dir . $name);
				$move = move_uploaded_file($_FILES['img']['tmp_name'], $dir . $name);
				if ($move) {
					$this->conn->query("UPDATE `product_list` set image_path = CONCAT('uploads/products/$name','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$pid}'");
				} else {
					$resp['msg'] .= " El logotipo no se ha podido cargar";
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if (isset($resp['msg']) && $resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}
	function delete_product()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `product_list` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Producto eliminado con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// Inicia Save y Delete Localidad

	function save_localidad()
	{
		$_POST['name'] = htmlentities($_POST['name']);
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `localidad` where `name` = '{$name}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "La Localidad ya existe";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `localidad` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `localidad` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			$pid = empty($id) ? $this->conn->insert_id : $id;
			$resp['id'] = $pid;
			if (empty($id))
				$resp['msg'] = "Nueva localidad guardada con éxito";
			else
				$resp['msg'] = "Localidad actualizada con éxito";
			if (!empty($_FILES['img']['tmp_name'])) {
				$ext = $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$dir = base_app . "uploads/localidad/";
				if (!is_dir($dir))
					mkdir($dir);
				$name = $pid . "." . $ext;
				if (is_file($dir . $name))
					unlink($dir . $name);
				$move = move_uploaded_file($_FILES['img']['tmp_name'], $dir . $name);
				if ($move) {
					$this->conn->query("UPDATE `localidad` set image_path = CONCAT('uploads/localidad/$name','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$pid}'");
				} else {
					$resp['msg'] .= " El logotipo no se ha podido cargar";
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if (isset($resp['msg']) && $resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}
	function delete_localidad()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `localidad` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Localidad eliminada con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// Fianliza Save y Delele Localidad


	// Inicia grabado de cofrades

	public function get_localidades(){
		extract($_GET);

		try {
			$localidades = $this->conn->query("SELECT * FROM localidad WHERE provincia_id = '{$provincia_id}' AND delete_flag = 0 ORDER BY des_localidad ASC")->fetch_all(MYSQLI_ASSOC);
			echo json_encode($localidades);
		} catch (Exception $e) {
			// Log the error for debugging
			error_log("Error in get_localidades: " . $e->getMessage());
	
			// Send an error response to the client
			http_response_code(500); // Internal Server Error
			echo json_encode(['error' => 'An error occurred while fetching localidades']);
		}
	  }

	  function save_cofrade()
	  {
		$resp = [];
		  $_POST['Nombre'] = htmlentities($_POST['Nombre']);
		  extract($_POST);
		  $data = "";
		  foreach ($_POST as $k => $v) {
			  if (!in_array($k, array('id'))) {
				  $v = $this->conn->real_escape_string($v);
				  if (!empty($data)) $data .= ",";
				  $data .= " `{$k}`='{$v}' ";
			  }
		  }
		  $check = $this->conn->query("SELECT * FROM `cofrades` where `Nombre` = '{$Nombre}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		  if ($this->capture_err())
			  return $this->capture_err();
		  if ($check > 0) {
			  $resp['status'] = 'failed';
			  $resp['msg'] = "El Cofrade ya existe";
			  return json_encode($resp);
			  exit;
		  }
	  
		  if (empty($id)) {
			  $sql = "INSERT INTO `cofrades` set {$data} ";
		  } else {
			  $sql = "UPDATE `cofrades` set {$data} where id = '{$id}' ";
		  }
		  $save = $this->conn->query($sql);
	  
		  if ($save) {
			  // Actualizar Numero_historico y numero_activo después de guardar
			  $update_query = "UPDATE cofrades
							  SET Numero_historico = id,
								  numero_activo = IF(status = 1, 
													 (SELECT COUNT(*) FROM cofrades AS c2 WHERE c2.id <= cofrades.id AND c2.status = 1), 
													 0)
							  WHERE Numero_historico = 0 OR numero_activo != (SELECT COUNT(*) FROM cofrades AS c2 WHERE c2.id <= cofrades.id AND c2.status = 1);";
			  $this->conn->query($update_query);
	  
			  // ... (resto del código para manejar la imagen y la respuesta)
		  } else {
			  $resp['status'] = 'failed';
			  $resp['err'] = $this->conn->error . "[{$sql}]";
		  }
		  if (isset($resp['msg']) && $resp['status'] == 'success') {
			  $this->settings->set_flashdata('success', $resp['msg']);
		  }
		  return json_encode($resp);
	  }
	  
	function delete_cofrade()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `cofrades` set `delete_flag` = 1  where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Cofrade eliminado con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}


	// Finbaliza grabado de cofrades


	function save_service()
	{
		extract($_POST);
		$data = "";
		$_POST['description'] = addslashes(htmlentities($description));
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `service_list` where `service` = '{$service}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "El servicio ya existe";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `service_list` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `service_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$this->settings->set_flashdata('success', "Nuevo servicio guardado con éxito");
			else
				$this->settings->set_flashdata('success', "Servicio actualizado con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_service()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `service_list` set delete_flag = 1 where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Servicio eliminado con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_stock()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (empty($id)) {
			$sql = "INSERT INTO `stock_list` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `stock_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$this->settings->set_flashdata('success', "Nuevo Inventario guardado con éxito");
			else
				$this->settings->set_flashdata('success', "Inventario actualizado correctamente");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_stock()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `stock_list` where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Inventario eliminado con éxito.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_mechanic()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `mechanics_list` where `name` = '{$name}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Técnico existe actualmente";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `mechanics_list` set {$data} ";
			$save = $this->conn->query($sql);
		} else {
			$sql = "UPDATE `mechanics_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$this->settings->set_flashdata('success', "Nuevo técnico guardado con éxito.");
			else
				$this->settings->set_flashdata('success', "Técnico actualizado con exito");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_mechanic()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `mechanics_list` where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Técnico eliminado con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_request()
	{
		if (empty($_POST['id']))
			$_POST['client_id'] = $this->settings->userdata('id');
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (in_array($k, array('client_id', 'service_type', 'mechanic_id', 'status'))) {
				if (!empty($data)) {
					$data .= ", ";
				}

				$data .= " `{$k}` = '{$v}'";
			}
		}
		if (empty($id)) {
			$sql = "INSERT INTO `service_requests` set {$data} ";
		} else {
			$sql = "UPDATE `service_requests` set {$data} where id ='{$id}' ";
		}
		$save = $this->conn->query($sql);
		if ($save) {
			$rid = empty($id) ? $this->conn->insert_id : $id;
			$data = "";
			foreach ($_POST as $k => $v) {
				if (!in_array($k, array('id', 'client_id', 'service_type', 'mechanic_id', 'status'))) {
					if (!empty($data)) {
						$data .= ", ";
					}
					if (is_array($_POST[$k]))
						$v = implode(",", $_POST[$k]);
					$v = $this->conn->real_escape_string($v);
					$data .= "('{$rid}','{$k}','{$v}')";
				}
			}
			$sql = "INSERT INTO `request_meta` (`request_id`,`meta_field`,`meta_value`) VALUES {$data} ";
			$this->conn->query("DELETE FROM `request_meta` where `request_id` = '{$rid}' ");
			$save = $this->conn->query($sql);
			if ($save) {
				$resp['status'] = 'success';
				$resp['id'] = $rid;
				if (empty($id))
					$resp['msg'] = " La solicitud de servicio se ha enviado con éxito.";
				else
					$resp['msg'] = " Los detalles de la solicitud de servicio se han actualizado correctamente";
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error;
				$resp['sql'] = $sql;
				if (empty($id))
					$resp['msg'] = " La solicitud de servicio no se ha podido enviar";
				else
					$resp['msg'] = " Los detalles de la solicitud de servicio no se han podido actualizar";
				$this->conn->query("DELETE FROM `service_requests` where id = '{$rid}'");
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			$resp['sql'] = $sql;
			if (empty($id))
				$resp['msg'] = " La solicitud de servicio no se ha podido enviar";
			else
				$resp['msg'] = " Los detalles de la solicitud de servicio no se han podido actualizar";
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata("success", $resp['msg']);
		return json_encode($resp);
	}
	function delete_request()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `service_requests` where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Solicitud eliminada con éxito");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_to_cart()
	{
		$_POST['client_id'] = $this->settings->userdata('id');
		extract($_POST);
		$check = $this->conn->query("SELECT * FROM `cart_list` where client_id = '{$client_id}' and product_id = '{$product_id}'")->num_rows;
		if ($check > 0) {
			$sql = "UPDATE `cart_list` set quantity = quantity + {$quantity}  where product_id = '{$product_id}' and client_id = '{$client_id}'";
		} else {
			$sql = "INSERT INTO `cart_list` set quantity = quantity + {$quantity}, product_id = '{$product_id}', client_id = '{$client_id}'";
		}
		$save = $this->conn->query($sql);
		if ($save) {
			$resp['status'] = 'success';
			$resp['cart_count'] = $this->conn->query("SELECT SUM(quantity) from cart_list where client_id = '{$client_id}'")->fetch_array()[0];
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = " El producto no se ha podido agregar en la lista del carrito.";
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_cart_quantity()
	{
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `cart_list` where id = '{$cart_id}'")->fetch_array();
		$pid = $get['product_id'];
		$stocks = $this->conn->query("SELECT SUM(quantity) FROM stock_list where product_id = '$pid'")->fetch_array()[0];
		$out = $this->conn->query("SELECT SUM(quantity) FROM order_items where product_id = '{$pid}' and order_id in (SELECT id FROM order_list where `status` != 5) ")->fetch_array()[0];
		$stocks = $stocks > 0 ? $stocks : 0;
		$out = $out > 0 ? $out : 0;
		$available = $stocks - $out;
		if ($available < 1) {
			$resp['status'] = 'failed';
			$resp['msg'] = " El producto no tiene existencias disponibles";
			$save = $this->conn->query("UPDATE cart_list set quantity = '0' where id = '{$cart_id}'");
		} elseif (eval("return " . $get['quantity'] . " " . $quantity . ";") < 1 && $available > 0) {
			$resp['status'] = 'failed';
			$save = $this->conn->query("UPDATE cart_list set quantity = '1' where id = '{$cart_id}'");
			$resp['msg'] = " Estás en la cantidad más baja";
		} elseif (eval("return " . $get['quantity'] . " " . $quantity . ";") > $available) {
			$resp['status'] = 'failed';
			$save = $this->conn->query("UPDATE cart_list set quantity = '{$available}' where id = '{$cart_id}'");
			$resp['msg'] = " El producto solo tiene [{$available}] unidades disponibles";
		} else {
			$resp['status'] = 'success';
			$save = $this->conn->query("UPDATE cart_list set quantity = quantity {$quantity} where id = '{$cart_id}'");
		}
		return json_encode($resp);
	}
	function remove_from_cart()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `cart_list` where id = '{$cart_id}'");
		if ($del) {
			$resp['status'] = 'success';
			$resp['msg'] = " El producto ha sido eliminado de la lista del carrito con éxito";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = " El producto no se ha podido eliminar de la lista del carrito";
			$resp['error'] = $this->conn->error;
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function place_order()
	{
		$_POST['client_id'] = $this->settings->userdata('id');
		extract($_POST);
		$pref = date("Ym-");
		$code = sprintf("%'.05d", 1);
		while (true) {
			$check = $this->conn->query("SELECT * FROM `order_list` where ref_code = '{$pref}{$code}'")->num_rows;
			if ($check > 0) {
				$code = sprintf("%'.05d", ceil($code) + 1);
			} else {
				break;
			}
		}
		$ref_code = $pref . $code;
		$sql1 = "INSERT INTO `order_list` (`ref_code`,`client_id`,`delivery_address`) VALUES ('{$ref_code}','{$client_id}','{$delivery_address}')";
		$save = $this->conn->query($sql1);
		if ($save) {
			$oid = $this->conn->insert_id;
			$data = "";
			$total_amount = 0;
			$cart = $this->conn->query("SELECT c.*,p.price FROM cart_list c inner join product_list p on c.product_id = p.id where c.client_id = '{$client_id}'");
			while ($row = $cart->fetch_assoc()) {
				if (!empty($data)) $data .= ", ";
				$data .= "('{$oid}','{$row['product_id']}','{$row['quantity']}')";
				$total_amount += ($row['price'] * $row['quantity']);
			}
			if (!empty($data)) {
				$sql2 = "INSERT INTO `order_items` (`order_id`,`product_id`,`quantity`) VALUES {$data}";
				$save2 = $this->conn->query($sql2);
				if ($save2) {
					$resp['status'] = 'success';
					$this->conn->query("DELETE FROM `cart_list` where client_id = '{$client_id}'");
					$this->conn->query("UPDATE `order_list` set total_amount = '{$total_amount}' where id = '{$oid}'");
					$resp['msg'] = " El pedido se ha realizado correctamente";
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = " No se ha podido realizar el pedido";
					$resp['error'] = $this->conn->error;
					$this->conn->query("DELETE FROM `order_list` where id = '{$oid}'");
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = " El carrito esta vacío";
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = " No se ha podido realizar el pedido";
			$resp['error'] = $this->conn->error;
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function cancel_order()
	{
		extract($_POST);
		$update = $this->conn->query("UPDATE `order_list` set status = 5 where id = '{$id}'");
		if ($update) {
			$resp['status'] = 'success';
			$resp['msg'] = " El pedido ha sido cancelado";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = " El pedido no se ha podido cancelar";
			$resp['error'] = $this->conn->error;
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['status']);
		return json_encode($resp);
	}
	function cancel_service()
	{
		extract($_POST);
		$update = $this->conn->query("UPDATE `service_requests` set status = 4 where id = '{$id}'");
		if ($update) {
			$resp['status'] = 'success';
			$resp['msg'] = " La solicitud de servicio ha sido cancelada";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = " La solicitud de servicio no se ha podido cancelar";
			$resp['error'] = $this->conn->error;
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['status']);
		return json_encode($resp);
	}
	function update_order_status()
	{
		extract($_POST);
		$update = $this->conn->query("UPDATE `order_list` set `status` = '{$status}' where id = '{$id}'");
		if ($update) {
			$resp['status'] = 'success';
			$resp['msg'] = " El estado del pedido se ha actualizado correctamente";
		} else {
			$resp['error'] = $this->conn->error;
			$resp['status'] = 'failed';
			$resp['msg'] = " El estado del pedido no se ha podido actualizar";
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function delete_order()
	{
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `order_list` where id = '{$id}'");
		if ($delete) {
			$resp['status'] = 'success';
			$resp['msg'] = " El estado del pedido se ha eliminado correctamente";
		} else {
			$resp['error'] = $this->conn->error;
			$resp['status'] = 'failed';
			$resp['msg'] = " El estado del pedido no se ha podido eliminar";
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
		break;
	case 'delete_category':
		echo $Master->delete_category();
		break;
	case 'save_brand':
		echo $Master->save_brand();
		break;
	case 'delete_brand':
		echo $Master->delete_brand();
		break;

	case 'save_cofrade':
		echo $Master->save_cofrade();
		break;
	case 'delete_cofrade':
		echo $Master->delete_cofrade();
		break;

	case 'save_banco':
		echo $Master->save_banco();
		break;
	case 'delete_banco':
		echo $Master->delete_banco();
		break;


	case 'save_provincia':
		echo $Master->save_provincia();
		break;
	case 'delete_provincia':
		echo $Master->delete_provincia();
		break;

		case 'get_localidades':
			echo $Master->get_localidades();
			break;
		


	case 'save_baja':
		echo $Master->save_baja();
		break;
	case 'delete_baja':
		echo $Master->delete_baja();
		break;


	case 'save_service':
		echo $Master->save_service();
		break;
	case 'delete_service':
		echo $Master->delete_service();
		break;
	case 'save_product':
		echo $Master->save_product();
		break;
	case 'delete_product':
		echo $Master->delete_product();
		break;


	case 'save_localidad':
		echo $Master->save_localidad();
		break;
	case 'delete_localidad':
		echo $Master->delete_localidad();
		break;






	case 'save_stock':
		echo $Master->save_stock();
		break;
	case 'delete_stock':
		echo $Master->delete_stock();
		break;
	case 'save_mechanic':
		echo $Master->save_mechanic();
		break;
	case 'delete_mechanic':
		echo $Master->delete_mechanic();
		break;
	case 'save_request':
		echo $Master->save_request();
		break;
	case 'delete_request':
		echo $Master->delete_request();
		break;
	case 'cancel_service':
		echo $Master->cancel_service();
		break;
	case 'save_to_cart':
		echo $Master->save_to_cart();
		break;
	case 'update_cart_quantity':
		echo $Master->update_cart_quantity();
		break;
	case 'remove_from_cart':
		echo $Master->remove_from_cart();
		break;
	case 'place_order':
		echo $Master->place_order();
		break;
	case 'cancel_order':
		echo $Master->cancel_order();
		break;
	case 'update_order_status':
		echo $Master->update_order_status();
		break;
	case 'delete_order':
		echo $Master->delete_order();
		break;
	default:
		// echo $sysset->index();
		break;
}
