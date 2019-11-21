<?php date_default_timezone_set('Asia/Jakarta'); session_start();

function base()
{
	return '';
}

//=============================================
if( isset($_SESSION['admin_login']) ){
	$ses = true;
} else{
	$ses = false;
}
//=============================================

function conn()
{
	return mysqli_connect('localhost', 'root', '' ,'tugas_webprogram');
}

function closeConn()
{
	return mysqli_close(conn());
}

function fetchData($tb, $ord)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$tb ){
			return false;
		} else{
			//$conn = mysqli_connect('localhost', 'root', '' ,'tugas_webprogram');
			$query = mysqli_query(conn(), "SELECT * FROM $tb ORDER BY $ord ASC");
			$array[] = [];	
			while ($fetch = mysqli_fetch_assoc( $query)) {
				$array[] = $fetch;
			}

			return $array;
			closeConn();
		}
	} else{
		return [];
	}
}

function fetchOrder()
{
	if( isset($_SESSION['admin_login']) ){
			//$conn = mysqli_connect('localhost', 'root', '' ,'tugas_webprogram');
		$query = mysqli_query(conn(), "SELECT * FROM tb_order JOIN pelanggan on tb_order.kd_pel = pelanggan.kd_pel ORDER BY tb_order.kd_tran DESC");
		$array[] = [];	
		while ($fetch = mysqli_fetch_assoc( $query)) {
			$array[] = $fetch;
		}

		return $array;
		closeConn();

	} else{
		return [];
	}
}

function get_nmBrg($kd)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$kd ){
			return false;
		} else{
			$query = mysqli_query(conn(), "SELECT nm_brg FROM barang WHERE kd_brg='$kd'");
			return mysqli_fetch_row( $query)[0];
			closeConn();
		}
	} else{
		return [];
	}
}

function fetchBrg()
{
	if( isset($_SESSION['admin_login']) ){
		$query = mysqli_query(conn(), "SELECT * FROM barang LEFT JOIN category ON barang.kd_kat = category.kd_kat ORDER BY category.nm_kat ASC, barang.nm_brg ASC");
		$array[] = [];	
		while ($fetch = mysqli_fetch_assoc( $query)) {
			$array[] = $fetch;
		}
		return $array;
	} else{
		return [];
	}
}

function countCat($kd)
{
	if( isset($_SESSION['admin_login']) ){
		$query = mysqli_query(conn(), "SELECT * FROM barang WHERE kd_kat = '$kd'");
		$array[] = [];	
		while ($fetch = mysqli_fetch_assoc( $query)) {
			$array[] = $fetch;
		}

		return $array;
	} else{
		return [];
	}
}

//================= ADMIN FUNCTION ================================================

function delCat($kd)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$kd ){
			return false;
		} else{
			$query = mysqli_query(conn(),"delete from category where kd_kat='$kd'");
			if( $query == true ){
				return true;
			} else{
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function updateCatName($data)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$data ){
			return false;
		} else{
			$kd_kat = $data['kd_kat'];
			$nm_kat = $data['nm_kat'];

			$check = countCat($kd_kat);
			if( $check > 1 ){
				$query = mysqli_query(conn(),"update category set nm_kat='$nm_kat' where kd_kat='$kd_kat'");
				if( $query == true ){
					return true;
				} else{
					return false;
				}
			} else if( $check == 1 ){
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function rupiah($val){
	
	$value = "Rp. " . number_format($val,2,',','.');
	return $value;

}

function addNewBrg($data)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$data ){
			return false;
		} else{
			$nm_brg = $data['nm_brg']; $kd_brg = $data['kd_brg']; $hrg_brg = $data['hrg_brg']; $kd_kat = $data['kd_kat']; $new_nm_kat = $data['new_nm_kat'];
			if( $kd_kat == 'add_new_cat' ){
				$insert_kat = add_new_cat($new_nm_kat);
				$kd_kat = $insert_kat['kd_kat'];
				$insert_brg = mysqli_query(conn(),"insert into barang values('$kd_brg','$nm_brg', '$hrg_brg', '$kd_kat')");
			} else{
				$insert_kat['stat'] = 'ok';
				$insert_brg = mysqli_query(conn(),"insert into barang values('$kd_brg','$nm_brg', '$hrg_brg', '$kd_kat')");
			}
			
			if( $insert_kat['stat'] == 'ok' and $insert_brg == true ){
				return 'Berhasil menambahkan '.$data['nm_brg'].' di dalam daftar barang dengan kode '.$data['kd_brg'];
			} else{
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function add_new_cat($nm_kat)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$nm_kat ){
			return false;
		} else{
			$kd_kat = 'kat_'.uniqid();
			$insert = mysqli_query(conn(),"insert into category values('$kd_kat','$nm_kat')");;
			if( $insert == true ){
				return ['stat' => 'ok', 'kd_kat' => $kd_kat];
			} else{
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function delBrg($kd)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$kd ){
			return false;
		} else{
			$query = mysqli_query(conn(),"delete from barang where kd_brg='$kd'");
			if( $query == true ){
				return true;
			} else{
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function deleteOrder($kd)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$kd ){
			return false;
		} else{
			$query = mysqli_query(conn(),"delete from tb_order where kd_tran='$kd'");
			if( $query == true ){
				return true;
			} else{
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function deletePel($kd)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$kd ){
			return false;
		} else{
			$query = mysqli_query(conn(),"delete from pelanggan where kd_pel='$kd'");
			if( $query == true ){
				return true;
			} else{
				return false;
			}
		}
	} else{
		return 'guest';
	}
}

function checkBrg($kd)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$kd ){
			return false;
		} else{
			$query = mysqli_query(conn(),"select * from barang where kd_brg='$kd'");
			return mysqli_num_rows($query);
		}
	} else{
		return 'guest';
	}
}

function updateDetBrg($data)
{
	if( isset($_SESSION['admin_login']) ){
		if( !$data ){
			return false;
		} else{
			$kd_brg = $data['kd_brg'];
			$nm_brg = $data['nm_brg'];
			$hrg_brg = $data['hrg_brg'];

			$check = checkBrg($kd_brg);
			if( $check == 0 ){
				return ['stat' => 'null'];
			} else if( $check > 0 ){
				$query = mysqli_query(conn(),"update barang set nm_brg='$nm_brg', hrg_brg='$hrg_brg' where kd_brg='$kd_brg'");
				if( $query == true ){
					return true;
				} else{
					return false;
				}
			}
		}
	} else{
		return 'guest';
	}
}

function adminCheck($id)
{
	if( $id ){
		return false;
	} else{
		$query = mysqli_query(conn(),"select * from user where id='$id'");
		return mysqli_num_rows($query);
	}
}

function adminLogin($data)
{
	if( !$data ){
		return false;
	} else{
		$username = $data['username'];
		$password = $data['password'];

		if( !$username or !$password ){
			return false;
		} else{
			$user = mysqli_query(conn(),"select * from user where username='$username'");
			$fetch = mysqli_fetch_assoc($user);
			if( $fetch == NULL ){
				echo "username tidak terdaftar";
			} else{
				if( password_verify($password, $fetch['password']) ){
					$_SESSION['admin_login'] = [
						'id_admin' => $fetch['id'],
						'nama_admin' => $fetch['name']
					];
					return true;
				} else{
					echo "password salah";
				}
			}
		}
	}
}
//================= ADMIN FUNCTION ================================================
//================= GUEST FUNCTION ================================================

function guest_testOrder()
{
	$query = mysqli_query(conn(), "SELECT * FROM tb_order LEFT JOIN pelanggan ON tb_order.kd_pel = pelanggan.kd_pel");
	$array[] = [];	
	while ($fetch = mysqli_fetch_assoc( $query)) {
		$array[] = $fetch;
	}

	return $array;
	closeConn();
}

function guest_fetchData()
{
	$query = mysqli_query(conn(), "SELECT * FROM barang LEFT JOIN category ON barang.kd_kat = category.kd_kat ORDER BY category.nm_kat ASC, barang.nm_brg ASC");
	$array[] = [];	
	while ($fetch = mysqli_fetch_assoc( $query)) {
		$array[] = $fetch;
	}

	return $array;
	closeConn();
}

function guest_fetchCat()
{
	$query = mysqli_query(conn(), "SELECT * FROM category ORDER BY nm_kat ASC");
	$array[] = [];	
	while ($fetch = mysqli_fetch_assoc( $query)) {
		$array[] = $fetch;
	}

	return $array;
	closeConn();
}

function guest_getBrg($kd)
{
	$query = mysqli_query(conn(), "SELECT * FROM barang LEFT JOIN category ON barang.kd_kat = category.kd_kat WHERE barang.kd_kat='$kd' ORDER BY barang.nm_brg ASC");
	$array[] = [];	
	while ($fetch = mysqli_fetch_assoc( $query)) {
		$array[] = $fetch;
	}

	return $array;
	closeConn();
}

function getHrg($id)
{
	if( !$id ){
		return false;
	} else{
		$sql = mysqli_query(conn(), "SELECT hrg_brg FROM barang WHERE kd_brg='$id'");
		return mysqli_fetch_row($sql)[0];
	}
}

function submit_newPel($data)
{
	if( !$data ){
		return false;
	} else{
		$kd_pel = $data['kd_pel'];
		$nm_pel = $data['nm_pel'];
		$almt_pel = $data['almt_pel'];
		$no_telp = $data['no_telp'];
		$insert = mysqli_query(conn(),"insert into pelanggan values('$kd_pel','$nm_pel', '$almt_pel', '$no_telp')");
		if( $insert == true ){
			return 'inserted';
		} else{
			return 'error';
		}
	}
}

function submit_newOrder($data)
{
	if( !$data ){
		return false;
	} else{
		$kd_tran = $data['kd_tran'];
		$kd_brg = $data['kd_brg'];
		$kd_pel = $data['kd_pel'];
		$jml = $data['jml'];
		$hrg_byr = $data['hrg_byr'];
		$insert = mysqli_query(conn(),"insert into tb_order values('$kd_tran','$kd_brg', '$kd_pel', '$jml', '$hrg_byr')");
		if( $insert == true ){
			return 'inserted';
		} else{
			var_dump($mysqli->connect_error);
			die;
		}
	}
}

function submitNewOrder($data)
{
	if( !$data ){
		return false;
	} else{
		$items = $data['items'];
		$qty = $data['qty'];

		$item = explode('?', $items);
		$jml = explode('?', $qty);

		$hrg = [];
		for( $i = 0; $i < count($item); $i++ ){
			$hrg[$i] = getHrg($item[$i]) * $jml[$i];
		}

		$data_pel = [
			'kd_pel' => 'pel_'.uniqid(),
			'nm_pel' => $data['nm_pel'],
			'almt_pel' => $data['almt_pel'],
			'no_telp' => $data['no_telp']
		];

		$data_order = [
			'kd_tran' => 'tran_'.uniqid(),
			'kd_brg' => $items,
			'kd_pel' => $data_pel['kd_pel'],
			'jml' => $qty,
			'hrg_byr' => array_sum($hrg)
		];

		$new_pel = submit_newPel($data_pel);
		if( $new_pel == 'inserted' ){
			$new_order = submit_newOrder($data_order);
			if( $new_order == 'inserted' ){
				echo "
				<script>
				var db = openDatabase('cartDB', '1.0', 'cartDB', 65535)
				db.transaction(function(transaction){
					var sql = 'DROP TABLE cartList;';
					transaction.executeSql(sql, undefined, function(){
						console.log('.');
						}, function(){console.log('.');});
						});
						</script>
						informasi pemesanan barang berhasil masuk ke database.
						";
					} else{
						echo "Ada kesalahan dalam mengorder barang";
					}
				} else{
					echo "Ada kesalahan dalam mengorder barang";
				}

			}
		}

//================= GUEST FUNCTION ================================================
//============== END FUNCTION ============================================

//============== CONDITION =============================================== 

		if( isset($_POST['submitOrder']) ){
			$data = [
				'nm_pel' => $_POST['nm_pel'],
				'almt_pel' => $_POST['almt_pel'],
				'no_telp' => $_POST['no_telp'],
				'items' => $_POST['hid_fixItem'],
				'qty' => $_POST['hid_fixQty']
			];

			$submit = submitNewOrder($data);
		}
//================= ADMIN CONDITION ================================================

		if( isset($_GET['del_order']) ){
			if( $_GET['del_order'] == 'true' ){
				$kd = $_GET['kd'];
				$pel = $_GET['kdpel'];
				$del = deleteOrder($kd);
				if( $del == false ){
					echo "ada kesalahan dalam menghapus data";
				} else{
					$delPel = deletePel($pel);
					if( $delPel == true ){
						header('Location: index.php');
					} else{
						echo "ada kesalahan dalam menghapus data";		
					}
				}
			} else{
				echo "ada kesalahan dalam menghapus data";
			}
		}

		if( isset($_POST['admin_login']) ){
			$data = [
				'username' => $_POST['username'],
				'password' => $_POST['pass']
			];

			$login = adminLogin($data);
			if( $login == false ){
				if( $login['stat'] == 'null' ){
					echo "username tidak terdaftar";
				}
			} else{
				header('Location: index.php');
			}
		}

		if( isset($_POST['goLogout']) ){
			session_unset();
			header('Location: index.php');
		}

		if( isset($_POST['update_det_brg']) ){
			if( $_POST['update_det_brg'] == 'true' ){
				$data = [
					'kd_brg' => $_POST['update_brg_kd_value'],
					'nm_brg' => $_POST['update_nm_brg_value'],
					'hrg_brg' => $_POST['update_hrg_brg_value']
				];
				$update = updateDetBrg($data);

				if( !$update['stat'] ){
					if( $update == false ){
						echo "ada kesalahan dalam merubah memperbarui informasi barang";
					} else{
						header('Location: index.php');
					}
				} else if( $update['stat'] == 'null' ){
					echo "barang yang dimaksud tidak ada dalam database";
				}
			} else{
				echo "ada kesalahan dalam merubah memperbarui informasi barang";
			}

		}

		if( isset($_GET['del_brg']) ){
			if( $_GET['del_brg'] == 'true' ){
				$kd_brg = $_GET['kd_brg'];
				if( !$kd_brg ){
					header('Location: index.php');
				} else{
					$del = delBrg($kd_brg);
					if( $del == false ){
						echo 'Ada kesalahan dalam menghapus '.$kd_brg.' dari daftar barang';
					} else{
						header('Location: index.php');
					}
				}
			} else{
				header('Location: index.php');
			}
		}

		if( isset($_POST['submit_new_brg']) ){
			$nama_brg = $_POST['nama_barang'];
			$harga_brg = $_POST['harga_barang'];
			$kode_kat = $_POST['pilih_kategori'];
			$new_nm_kat = $_POST['new_nm_kat'];
			if( $nama_brg == '' or $harga_brg == '' or $kode_kat == '' ){
				echo 'Maaf, semua field harus diisi!';
			} else{
				$kd_brg = 'brg_'.uniqid();
				$data = [
					'kd_brg' => $kd_brg,
					'nm_brg' => $nama_brg,
					'hrg_brg' => $harga_brg,
					'kd_kat' => $kode_kat,
					'new_nm_kat' => $new_nm_kat
				];

				$inserting = addNewBrg($data);
				if( $inserting == false ){
					$insert = "Ada kesalahan dalam menambahkan database";
				} else if( $inserting == 'guest' ){
					$insert = "Anda tidak memiliki hak admin.";
				} else{
					$insert = $inserting;
				}

				echo $insert;
			}
		}

		if( isset($_POST['rename_nm_kat']) ){
	// $kd_kat = $_POST['rename_nm_kd_kat'];
	// $nm_kat = $_POST['rename_nm_kat_value'];

			$data = [
				'kd_kat' => $_POST['rename_nm_kd_kat'],
				'nm_kat' => $_POST['rename_nm_kat_value']
			];
			$update = updateCatName($data);

			if( $update == false ){
				echo "ada kesalahan dalam merubah nama kategori";
			} else{
				header('Location: index.php');
			}

		}

		if( isset($_GET['del_kd_kat'])){
			$kd_kat = $_GET['del_kd_kat'];

			if( !$kd_kat ){
				header('Location: index.php');
			} else{
				$del = delCat($kd_kat);
				if( $del == false ){
					echo 'Ada kesalahan dalam menghapus '.$kd_kat.' dari kategori';
				} else{
					header('Location: index.php');
				}
			}
		}

		if( isset($_POST['submit_new_cat']) ){
			$inp_nama_kat = $_POST['nama_kategori'];
			if( $inp_nama_kat == '' ){
				echo 'Maaf, nama kategori tidak boleh kosong!';
			} else{
				$nama_kat = $inp_nama_kat;

				$kd_kat = 'kat_'.uniqid();
				$data = [
					'kd_kat' => $kd_kat,
					'nm_kat' => $nama_kat
				];

				$inserting = addNewCat($data);
				if( $inserting == false ){
					$insert = "Ada kesalahan dalam menambahkan database";
				} else{
					$insert = $inserting;
				}

				echo $insert;
			}
		}
//================= ADMIN CONDITION ================================================
		?>

		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />

			<title>Tugas Pemrograman Web</title>
		</head>
		<body>
			<a href="" id="reload"></a>
			<div class="container-fluid mb-3 mt-3">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="barang-tab" data-toggle="tab" href="#barang" role="tab" aria-controls="barang">Admin</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pelanggan-tab" data-toggle="tab" href="#pelanggan" role="tab" aria-controls="pelanggan">Client</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active mt-2 mb-2" id="barang" role="tabpanel" aria-labelledby="barang-tab">
						<div class="container-fluid">
							<div class="admin-panel row">
								<div class="col-2">
									<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
										<a class="nav-link active" id="v-pills-category-tab" data-toggle="pill" href="#v-pills-category" role="tab" aria-controls="v-pills-category">Kategori Barang</a>
										<a class="nav-link" id="v-pills-stok-tab" data-toggle="pill" href="#v-pills-stok" role="tab" aria-controls="v-pills-stok">Stok Barang</a>
										<a class="nav-link" id="v-pills-order_masuk-tab" data-toggle="pill" href="#v-pills-order_masuk" role="tab" aria-controls="v-pills-order_masuk">Order Masuk</a>
										<form class="mt-5" method="post" action="">
											<input class="btn btn-sm btn-primary" type="submit" value="Logout" name="goLogout" onclick="return confirm('lanjutkan untuk logout?')">
										</form>
									</div>
								</div>
								<div class="col-10">
									<div class="tab-content" id="v-pills-tabContent">
										<div class="tab-pane fade show active" id="v-pills-category" role="tabpanel" aria-labelledby="v-pills-category-tab">
											<table class="table table-hover mt-2 mb-2">
												<thead>
													<tr>
														<th scope="col">No</th>
														<th scope="col">Kode Kategori</th>
														<th scope="col">Nama Kategori</th>
														<th scope="col" class="text-center">Jumlah Barang</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php 

													$category = fetchData('category', 'nm_kat');
													unset($category[0]);
													$i = 1;
													foreach ($category as $cat) {
														$count = countCat($cat['kd_kat']);
														if( $count == NULL ){
															$data = 0;
														} else{
															$data = count($count);
														} ?>
														<tr>
															<th scope="row"><?= $i++ ?></th>
															<td><?= $cat['kd_kat'] ?></td>
															<td><?= $cat['nm_kat'] ?></td>
															<td class="text-center"><?= $data - 1 ?></td>
															<td>
																<div class="row">
																	<button data-toggle="modal" data-target="#updateKatName_<?= $cat['kd_kat'] ?>" class="col-md btn btn-sm btn-warning mr-2 mb-2">Update</button>
																	<button id="deleteCat" onclick="deleteCat(this)" data-kd="<?= $cat['kd_kat'] ?>" data-nm="<?= $cat['nm_kat'] ?>" class="col-md btn btn-sm btn-danger mb-2 mr-2">Delete</button>
																	<!-- Modal -->
																	<div class="modal fade" id="updateKatName_<?= $cat['kd_kat'] ?>" tabindex="-1" role="dialog" aria-labelledby="updateKatName_<?= $cat['kd_kat'] ?>Label" aria-hidden="true">
																		<div class="modal-dialog" role="document">
																			<div class="modal-content">
																				<div class="modal-header">
																					<h5 class="modal-title" id="updateKatName_<?= $cat['kd_kat'] ?>Label">Rubah Nama Kategori</h5>
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						<span aria-hidden="true">&times;</span>
																					</button>
																				</div>
																				<div class="modal-body">
																					<div class="modal-body">
																						<form action="" method="post">
																							<div class="form-group">
																								<input type="hidden" class="form-control text-center" id="rename_nm_kd_kat" readonly="readonly" name="rename_nm_kd_kat" value="<?= $cat['kd_kat'] ?>">
																								<label for="rename_nm_kat_value">Nama Kategori</label>
																								<input type="text" class="form-control" id="rename_nm_kat_value" name="rename_nm_kat_value" value="<?= $cat['nm_kat'] ?>">
																							</div>
																							<button type="submit" name="rename_nm_kat" class="btn btn-warning" onclick="return confirm('yakin ingin merubah kategori <?= $cat['nm_kat'] ?> dengan nama yang baru?')">Rubah</button>
																						</form>
																					</div>
																				</div>
																				<div class="modal-footer"></div>
																			</div>
																		</div>
																	</div>
																</div>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<div class="tab-pane fade" id="v-pills-stok" role="tabpanel" aria-labelledby="v-pills-stok-tab">
											<div class="container mt-3 mb-2">
												<button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#tambahBarangBaru">Tambah Barang</button>
												<div class="clearfix"></div>
												<!-- modal tambah kategori -->
												<div class="modal fade" id="tambahBarangBaru" tabindex="-1" role="dialog" aria-labelledby="tambahBarangBaruLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="tambahBarangBaruLabel">Tambah Barang</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<form action="" method="post">
																	<div class="form-group">
																		<label for="pilih_kategori">Kategori</label>
																		<select class="form-control" name="pilih_kategori" id="pilih_kategori" required onchange="selectValue()">
																			<option value="">Pilih Kategori</option>
																			<?php 
																			$category = fetchData('category', 'nm_kat');
																			unset($category[0]);
																			foreach ($category as $cat) { ?>
																				<option value="<?= $cat['kd_kat']; ?>"><?= $cat['nm_kat']; ?></option>
																			<?php } ?>
																			<option class="text-center" id="option_new_cat" value="add_new_cat">atau tambah kategori baru ...</option>
																		</select>
																		<!-- modal tambah kategori -->
																		<div class="modal fade" id="tambahKategoriDisini" tabindex="-1" role="dialog" aria-labelledby="tambahKategoriDisiniLabel" aria-hidden="true">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="tambahKategoriDisiniLabel">Tambah Kategori Baru</h5>
																					</div>
																					<div class="modal-body">
																						<div class="form-group">
																							<label for="nama_kategori">Nama Kategori</label>
																							<input type="text" class="form-control" id="nama_kategori_baru" name="nama_kategori_baru">
																						</div>
																						<button type="button" id="save_new_kat_name" class="btn btn-primary">Simpan</button>
																						<input type="hidden" name="new_nm_kat" id="new_nm_kat">
																					</div>
																					<div class="modal-footer"></div>
																				</div>
																			</div>
																		</div>
																		<label for="nama_barang">Nama Barang</label>
																		<input type="text" class="form-control" id="nama_barang" name="nama_barang">
																		<label for="harga_barang">Harga Barang</label>
																		<input type="text" class="form-control" id="harga_barang" name="harga_barang">
																	</div>
																	<button type="submit" name="submit_new_brg" class="btn btn-primary">Tambahkan</button>
																</form>
															</div>
															<div class="modal-footer"></div>
														</div>
													</div>
												</div>
											</div>
											<table class="table table-hover mt-2 mb-2">
												<thead>
													<tr>
														<th scope="col">No</th>
														<th scope="col">Kode Barang</th>
														<th scope="col">Nama Barang</th>
														<th scope="col" class="text-center">Harga Barang</th>
														<th>Kategori</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php 

													$barang = fetchBrg();
													unset($barang[0]);
													$i = 1;
													foreach ($barang as $cat) { ?>
														<tr>
															<th scope="row"><?= $i++ ?></th>
															<td><?= $cat['kd_brg'] ?></td>
															<td><?= $cat['nm_brg'] ?></td>
															<td class="text-center"><?= rupiah($cat['hrg_brg']); ?></td>
															<td><?= $cat['nm_kat'] ?></td>
															<td>
																<div class="row">
																	<button data-toggle="modal" data-target="#update_brg_<?= $cat['kd_brg'] ?>" class="col-md btn btn-sm btn-warning mr-2 mb-2">Update</button>
																	<button id="deleteCat" onclick="deleteBrg(this)" data-kd="<?= $cat['kd_brg'] ?>" data-nm="<?= $cat['nm_brg'] ?>" class="col-md btn btn-sm btn-danger mb-2 mr-2">Delete</button>
																	<!-- Modal -->
																	<div class="modal fade" id="update_brg_<?= $cat['kd_brg'] ?>" tabindex="-1" role="dialog" aria-labelledby="update_brg_<?= $cat['kd_brg'] ?>Label" aria-hidden="true">
																		<div class="modal-dialog" role="document">
																			<div class="modal-content">
																				<div class="modal-header">
																					<h5 class="modal-title" id="update_brg_<?= $cat['kd_brg'] ?>Label">Rubah Data Barang</h5>
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						<span aria-hidden="true">&times;</span>
																					</button>
																				</div>
																				<div class="modal-body">
																					<div class="modal-body">
																						<form action="" method="post">
																							<div class="form-group">
																								<input type="hidden" class="form-control text-center" id="update_det_brg" readonly="readonly" name="update_det_brg" value="true" required>
																								<input type="hidden" class="form-control text-center" id="update_brg_kd_value" readonly="readonly" name="update_brg_kd_value" value="<?= $cat['kd_brg'] ?>" required>
																								<label for="update_nm_brg_value">Nama Barang</label>
																								<input type="text" class="form-control mb-3" id="update_nm_brg_value" name="update_nm_brg_value" value="<?= $cat['nm_brg'] ?>">
																								<label for="update_hrg_brg_value">Harga Barang</label>
																								<input type="text" class="form-control mb-3" id="update_hrg_brg_value" name="update_hrg_brg_value" value="<?= $cat['hrg_brg'] ?>">
																							</div>
																							<button type="submit" name="rename_nm_kat" class="btn btn-warning" onclick="return confirm('yakin ingin merubah informasi <?= $cat['nm_brg'] ?> dengan data yang baru?')">Rubah</button>
																						</form>
																					</div>
																				</div>
																				<div class="modal-footer"></div>
																			</div>
																		</div>
																	</div>
																</div>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<div class="tab-pane fade" id="v-pills-order_masuk" role="tabpanel" aria-labelledby="v-pills-order_masuk-tab">
											<table class="table table-hover">
												<thead>
													<tr>
														<th scope="col">No</th>
														<th scope="col">Kode Transaksi</th>
														<th scope="col">Pelanggan</th>
														<th scope="col">Total Tagihan</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php 
													$order = fetchOrder();
													unset($order[0]);
													$xi = 1;
													foreach ($order as $o) { ?>
														<tr>
															<th scope="row"><?= $xi++ ?></th>
															<td><?= $o['kd_tran'] ?></td>
															<td><?= ucwords($o['nm_pel']) ?></td>
															<td><?= rupiah($o['hrg_byr']) ?></td>
															<td width="16%">
																<div class="row">
																	<div class="col-md"><button class="btn btn-sm btn-info" data-toggle="modal" data-target="#det_order_<?= $o['kd_tran'] ?>">Detail</button></div>
																	<div class="col-md"><button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete_order<?= $o['kd_tran'] ?>">Hapus</button></div>
																</div>
															</td>
														</tr>
														<!-- Modal delete -->
														<div class="modal fade" id="delete_order<?= $o['kd_tran'] ?>" tabindex="-1" role="dialog" aria-labelledby="delete_order<?= $o['kd_tran'] ?>Label" aria-hidden="true">
															<div class="modal-dialog modal-sm" role="document">
																<div class="modal-content">
																	<div class="modal-body">
																		Hapus data ini?
																	</div>
																	<div class="modal-footer">
																		<button type="button" data-kdtran="<?= $o['kd_tran'] ?>" onclick="deleteOrder(this)" data-kdpel="<?= $o['kd_pel'] ?>" class="btn btn-sm btn-danger" data-dismiss="modal">Hapus</button>
																	</div>
																</div>
															</div>
														</div>

														<!-- Modal detail -->
														<div class="modal fade" id="det_order_<?= $o['kd_tran'] ?>" tabindex="-1" role="dialog" aria-labelledby="det_order_<?= $o['kd_tran'] ?>Label" aria-hidden="true">
															<div class="modal-dialog modal-lg" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="det_order_<?= $o['kd_tran'] ?>Label"><?= strtoupper($o['kd_tran']) ?></h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body card">
																		<div class="data-pelanggan card-body">
																			<h6 class="text-center">Data Pelanggan</h6>
																			<div class="row">
																				<div class="col-md-3">Nama Pelanggan</div>
																				<div class="col-md">:</div>
																				<div class="col-md-8"><?= ucwords($o['nm_pel']) ?></div>
																			</div>
																			<div class="row">
																				<div class="col-md-3">Alamat</div>
																				<div class="col-md">:</div>
																				<div class="col-md-8"><?= $o['almt_pel'] ?></div>
																			</div>
																			<div class="row">
																				<div class="col-md-3">Telepon</div>
																				<div class="col-md">:</div>
																				<div class="col-md-8"><?= $o['no_telp'] ?></div>
																			</div>
																		</div>
																		<div class="data_pemesanan card-body">
																			<h6 class="text-center">Data Pemesanan Barang</h6>
																			<?php 
																			$qty = explode('?', $o['jml']);
																			$item = explode('?', $o['kd_brg']);
																			$price = [];
																			$xx = 1;
																			for( $i=0; $i < count($item); $i++ ){
																				$price[$i] = $qty[$i] * getHrg($item[$i]);
																				?>

																				<div class="row">
																					<div class="col-md-0"><?= $xx++ ?>.</div>
																					<div class="col-md-2"><?= $qty[$i] ?> pcs</div>
																					<div class="col-md-6"><?= get_nmBrg($item[$i]) ?></div>
																					<div class="col-md-0"> = </div>
																					<div class="col-md"><?= rupiah($price[$i]) ?></div>
																				</div>
																			<?php } 

																			$totl = array_sum($price);
																			?>

																			<h6 class="text-center border border-info rounded p-2 mt-2">TOTAL TAGIHAN <?= rupiah($o['hrg_byr']) ?></h6>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																	</div>
																</div>
															</div>
														</div>
														<?php
													} ?>
												</tbody>
											</table>
										</div>
									</div>

								</div>
							</div>
							<div class="login-panel row" style="display: none">
								<div class="col-md"></div>
								<div class="col-md">
									<div class="mx-auto">
										<div class="login-form border border-primary border-2 rounded-right bg-primary shadow-lg">
											<h4 class="mt-3 text-center">Admin Login</h4>
											<form class="login-submit px-5 py-5" action="" method="post">
												<div class="form-group">
													<input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
												</div>
												<div class="form-group">
													<input type="password" class="form-control" id="pass" name="pass" placeholder="Password" required>
												</div>
												<div class="form-group">
													<div class="g-recaptcha" data-sitekey="6LdGC7sUAAAAAG1X5S7mbP59NeO8oFDI7ETYhlMx" data-size="small"></div>
													<small id="errcaptcha" style="display: none" class="form-text text-danger font-weight-bold text-monospace">Please check Recaptcha!!</small>
												</div>
												<button type="submit" name="admin_login" class="btn btn-light">Login</button>
											</form>
										</div>
									</div>
								</div>
								<div class="col-md"></div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade mt-2 mb-2" id="pelanggan" role="tabpanel" aria-labelledby="pelanggan-tab">
						<div class="container-fluid">
							<div class="container-fluid">
								<!-- Button trigger modal -->
								<button type="button" class="btn btn-outline-info float-right" id="guest_cartOpen" data-toggle="modal" data-target="#myCart">
									<small></small><i class="fas fa-shopping-cart"></i>
								</button> <div class="clearfix"></div>
								<h5 class="text-center mb-5">Halaman Order Barang</h5>

								<!-- Modal -->
								<div class="modal fade" id="myCart" tabindex="-1" role="dialog" aria-labelledby="myCartLabel" aria-hidden="true">
									<div class="modal-dialog modal-xl" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="myCartLabel">Keranjang Belanja</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<table class="table table-hover" id="guest_cartList" width="100%"></table>
												<table class="table table-hover" width="100%">
													<tr>
														<td width="71.5%"></td>
														<td width="28.5%" id="guest_totalCart"></td>
													</tr>
												</table>
											</div>
											<div class="modal-footer"></div>
										</div>
									</div>
								</div>
								<!-- Modal Info Pelanggan -->
								<div class="modal fade" id="dataPelanggan" tabindex="-1" role="dialog" aria-labelledby="dataPelangganLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="dataPelangganLabel">Data Pelanggan</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<form action="" method="post">
													<div class="form-group">
														<label for="exampleInputEmail1">Nama Lengkap</label>
														<input type="text" class="form-control text-capitalize" name="nm_pel" required>
													</div>
													<div class="form-group">
														<label for="exampleInputPassword1">Alamat</label>
														<textarea class="form-control" rows="6" name="almt_pel" required></textarea>
													</div>
													<div class="form-group">
														<label for="exampleInputEmail1">Telepon</label>
														<input type="text" class="form-control text-capitalize" onkeypress="return isNumberKey(event)" name="no_telp" required>
													</div>
													<div id="div_hid">
														<input type="hidden" id="hid_fixItem" name="hid_fixItem" required>
														<input type="hidden" id="hid_fixQty" name="hid_fixQty" required>
													</div>
													<div class="text-center">
														<button type="submit" id="submitNewOrder_fix" name="submitOrder" class="btn btn-primary">Order</button>
													</div>
												</form>
											</div>
											<div class="modal-footer"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
											<h6>Categories</h6>
											<a class="nav-link" id="v-pills-cat_all-tab" data-toggle="pill" href="#v-pills-cat_all" role="tab" aria-controls="v-pills-cat_all" aria-selected="true">Semua Barang</a>
											<?php 
											$cate = guest_fetchCat('cat');
											unset($cate[0]);
											foreach ($cate as $cat) { ?>
												<a class="nav-link" id="v-pills-cat_<?= $cat['kd_kat']; ?>-tab" data-toggle="pill" href="#v-pills-cat_<?= $cat['kd_kat']; ?>" role="tab" aria-controls="v-pills-cat_<?= $cat['kd_kat']; ?>" aria-selected="true"><?= $cat['nm_kat'] ?></a>
											<?php } ?>
										</div>
									</div>
									<div class="col-9">
										<div class="tab-content" id="v-pills-tabContent">
											<div class="tab-pane fade" id="v-pills-cat_all" role="tabpanel" aria-labelledby="v-pills-cat_all-tab">
												<ul class="list-group">
													<?php 
													$all_barang = guest_fetchData();
													unset($all_barang[0]);
													foreach ($all_barang as $abr) { ?>
														<li class="list-group-item d-flex justify-content-between align-items-center">
															<?= $abr['nm_brg'] . ' - ' . rupiah($abr['hrg_brg']) ?> 
															<span style="cursor: pointer" onclick="order(this)" data-nmbr="<?= $abr['nm_brg'] ?>" data-kdbr="<?= $abr['kd_brg'] ?>" data-hrg="<?= $abr['hrg_brg'] ?>" data-kdkat="<?= $abr['kd_kat'] ?>" class="badge badge-primary badge-pill">Order</span>
														</li>
													<?php } ?>
												</ul>
											</div>
											<?php 
											$barang = guest_fetchData();
											unset($barang[0]);
											foreach ($barang as $brg) { ?>
												<div class="tab-pane fade" id="v-pills-cat_<?= $brg['kd_kat'] ?>" role="tabpanel" aria-labelledby="v-pills-cat_<?= $brg['kd_kat'] ?>-tab">
													<ul class="list-group">
														<?php 
														$barangInCat = guest_getBrg($brg['kd_kat']);
														unset($barangInCat[0]);
														foreach ($barangInCat as $bic) { ?>
															<li class="list-group-item d-flex justify-content-between align-items-center">
																<?= $bic['nm_brg'] . ' - ' . rupiah($abr['hrg_brg']) ?>
																<span style="cursor: pointer" onclick="order(this)" data-nmbr="<?= $bic['nm_brg'] ?>" data-kdbr="<?= $bic['kd_brg'] ?>" data-hrg="<?= $bic['hrg_brg'] ?>" data-kdkat="<?= $bic['kd_kat'] ?>" class="badge badge-primary badge-pill">Order</span>
															</li> 
														<?php } ?>
													</ul>
												</div>
											<?php } ?>
											<div class="tab-pane fade show active" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab">Selamat Datang di halaman Order</div>
											<!-- Modal Order -->
											<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h6 class="modal-title" id="orderModalLabel">Order Barang</h6>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<div class="row">
																<div class="col-md" id="order_nmbr"></div>
																<div class="col-md text-right">
																	Jumlah <input style="width: 70px" class="form-group ml-2" type="text" onkeypress="return isNumberKey(event)" onkeyup="hitTotal(this)" id="jumOrder" value="1" min="1">
																</div>
																<div class="col-md text-right" id="order_hrgbr"></div>
															</div>
															<div class="text-right">
																<button class="btn btn-sm btn-primary" onclick="addCart()">Masukkan Keranjang</button>
															</div>
															<input type="hidden" id="hid_hrg">
															<input type="hidden" id="hid_total">
															<input type="hidden" id="hid_kdbr">
															<input type="hidden" id="hid_kdkat">
															<input type="hidden" id="hid_nmbr">
														</div>
														<div class="modal-footer"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

			<?php 

			if( $ses == false){
				echo '
				<script type="text/javascript">
				document.querySelector("#pelanggan-tab").click();
				jQuery( ".admin-panel" ).fadeOut();
				jQuery( ".btn_logout" ).fadeOut();
				jQuery(".login-panel").fadeIn("slow");
				</script>
				';
			} else if( $ses['stat'] == 'true' ){
				echo '
				<script type="text/javascript">
				jQuery( ".admin-panel" ).fadeIn();
				jQuery( ".btn_logout" ).fadeIn();
				jQuery(".login-panel").fadeOut("slow");
				</script>
				';
			}


			?>

			<script type="text/javascript">
				var hid_hrg = document.querySelector('#hid_hrg'),
				hid_kdbr = document.querySelector('#hid_kdbr'),
				hid_kdkat = document.querySelector('#hid_kdkat'),
				jumOrder = document.querySelector('#jumOrder'),
				hid_total = document.querySelector('#hid_total'),
				hid_nmbr = document.querySelector('#hid_nmbr'),
				hid_fixItem = document.querySelector('#hid_fixItem'),
				hid_fixQty = document.querySelector('#hid_fixQty'),
				db = openDatabase('cartDB', '1.0', 'cartDB', 65535);

				$('#submitNewOrder_fix').click(function(){
					return confirm('Sudah mengisi semua data dengan benar?');
				});

				function deleteOrder(e)
				{
					var kd_tran = e.dataset.kdtran,
						kd_pel = e.dataset.kdpel;
					location.href = 'index.php?del_order=true&kd='+kd_tran+'&kdpel='+kd_pel;
				}

				function kosongkan()
				{
					if( !confirm('Kosongkan keranjang belanja?') )return;
					$('#myCart').modal('hide');
					var db = openDatabase('cartDB', '1.0', 'cartDB', 65535)
					db.transaction(function(transaction){
						var sql = 'DROP TABLE cartList;';
						transaction.executeSql(sql, undefined, function(){
							console.log('.');
						}, function(){console.log('.');});
					});
				}

				function fixPay()
				{
					if( !confirm('ingin melanjutkan ke pembayaran?') )return ;
					$('#myCart').modal('hide');
					$('#dataPelanggan').modal('show');
				}

				$('#guest_cartOpen').click(function(){
					$('#guest_cartList').children().remove();
					$('#guest_totalCart').children().remove();
					hid_fixItem.value = '';
					hid_fixQty.value = '';
					db.transaction(function(transaction){
						var sql = 'SELECT * FROM cartList';
						transaction.executeSql(sql,undefined,function(transaction,result){
							if( result.rows.length ){
								var collections = [],
								allItems = [],
								allQty = [];
								for(var i=0; i < result.rows.length; i++){
									var row = result.rows.item(i);
									var item = row.item;
									var item_name = row.item_name;
									var item_cat = row.item_cat;
									var item_price = row.item_price;
									var qty = row.qty;
									var jum = qty * item_price;
									allItems[i] = item;
									allQty[i] = qty;
									collections[i] = jum;
									$('#guest_cartList').append('<tr><td width="40%">'+item_name+'</td><td width="20%">Rp. '+formatRupiah(item_price)+'</td><td width="5%">'+' x '+'</td><td width="10%">'+qty+'</td><td width="25%">= Rp. '+formatRupiah(jum)+'</td><tr>');
								}

								$('#guest_totalCart').append('<p>Total = Rp. '+formatRupiah(collections.reduce((a, b) => a + b, 0))+'</p><br><button class="btn btn-sm btn-warning mr-1" onclick="kosongkan()">Kosongkan</button><button class="btn btn-sm btn-primary" onclick="fixPay()">Lanjut Pembayaran</button>');
								hid_fixItem.value = allItems.join('?');
								hid_fixQty.value = allQty.join('?');
								console.log(collections);
							} else{
								$('#guest_cartList').append('<tr><td colspan="3" align="center">No Item Found in Cart</td></tr>');
							}
						}, function(transaction,err){
							$('#guest_cartList').children().remove();
							$('#guest_cartList').append('<tr><td colspan="3" align="center">No Item Found in Cart</td></tr>');
							console.log(err.message);
						});
					});
				});

				function addCart()
				{
					var item = hid_kdbr.value,
					item_name = hid_nmbr.value,
					item_cat = hid_kdkat.value,
					item_price = hid_hrg.value,
					qty = jumOrder.value;

					db.transaction(function(transaction){
						var sql = 'INSERT INTO cartList(item, item_name, item_cat, item_price, qty)'+
						'VALUES(?,?,?,?,?)';
						transaction.executeSql(sql, [item,item_name,item_cat,item_price,qty],function(){
							console.log('new item is added successfully');
						}, function(transaction,err){
							console.log(err.message);
						});
					});

					$('#orderModal').modal('hide');
				}


				function formatRupiah(angka, prefix){
					var number_string = angka.toString(),
					split   		= number_string.split(','),
					sisa     		= split[0].length % 3,
					rupiah     		= split[0].substr(0, sisa),
					ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

		function hitTotal(e)
		{
			var order_nmbr = document.querySelector('#order_nmbr'),
			order_hrgbr = document.querySelector('#order_hrgbr'),
			hid_total = document.querySelector('#hid_total'),
			hid_hrg = document.querySelector('#hid_hrg');

			var total = Number(hid_hrg.value * e.value);

			order_hrgbr.innerHTML = 'Total Rp. '+formatRupiah(total);
			hid_total.value = total;
		}

		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;

			return true;
		}

		function order(e)
		{
			var order_nmbr = document.querySelector('#order_nmbr'),
			order_hrgbr = document.querySelector('#order_hrgbr'),
			hid_hrg = document.querySelector('#hid_hrg'),
			hid_kdbr = document.querySelector('#hid_kdbr'),
			hid_kdkat = document.querySelector('#hid_kdkat'),
			nmbr = e.dataset.nmbr,
			kdbr = e.dataset.kdbr,
			hrg = e.dataset.hrg,
			kdkat = e.dataset.kdkat;

			hid_nmbr.value = nmbr;
			hid_hrg.value = hrg;
			jumOrder.value = 1;
			hid_total.value = Number(jumOrder.value * hid_hrg.value);
			hid_kdbr.value = kdbr;
			hid_kdkat.value = kdkat;
			order_nmbr.innerHTML = nmbr + ' Rp. '+formatRupiah(hrg);
			order_hrgbr.innerHTML = 'Total Rp. '+formatRupiah(hrg);
			$('#orderModal').modal('show');
			db.transaction(function(transaction){
				var sql = 'CREATE TABLE cartList '+
				'(id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,'+
				'item VARCHAR (30) NOT NULL,'+
				'item_name VARCHAR (100) NOT NULL,'+
				'item_cat VARCHAR (30) NOT NULL,'+
				'item_price INT NOT NULL,'+
				'qty INT NOT NULL)';
				transaction.executeSql(sql,undefined, function(){
					console.log('Succes to create table');
				}, function(){
					console.log('Table is already');
				});
			});
			console.log(sessionStorage.getItem('guest_order'));
		}

		$(".login-submit").on('submit', function(e){
			var recaptcha = $('.g-recaptcha-response').val(),
			err = $('#errcaptcha');
			if( recaptcha === '' ){
				e.preventDefault();
				err.fadeIn();
			} 
		});
		
		function deleteCat(e)
		{
			var kd = e.dataset.kd,
			nm = e.dataset.nm;
			if( confirm('yakin ingin menghapus '+nm+' dari daftar kategori dan menghapus semua barang di kategori '+nm+'?') == true ){
				console.log(kd);
				location.href = 'index.php?del_kd_kat='+kd;
			} else{
				return false;
			}
		}

		function deleteBrg(e)
		{
			var kd = e.dataset.kd,
			nm = e.dataset.nm;
			if( confirm('yakin ingin menghapus '+nm+' dari daftar barang?') == true ){
				console.log(kd);
				location.href = 'index.php?del_brg=true&kd_brg='+kd;
			} else{
				return false;
			}
		}

		function selectValue()
		{
			var val = document.getElementById('pilih_kategori').value;
			if( val == 'add_new_cat' ){
				console.log(val);
				$('#tambahKategoriDisini').modal('show'); 
				document.querySelector('#save_new_kat_name').onclick = function(){
					document.querySelector('#new_nm_kat').value = document.querySelector('#nama_kategori_baru').value;
					document.querySelector('#option_new_cat').innerHTML = document.querySelector('#nama_kategori_baru').value;
					$('#tambahKategoriDisini').modal('hide'); 
				};
			} else{
				document.querySelector('#option_new_cat').innerHTML = 'atau tambah kategori baru ...';
			}
		}


	</script>

</body>
</html>