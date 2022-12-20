<?php

require_once("dahili.php");
$app = new Stok;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<link rel="stylesheet" href="tasarim.css" />
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>STOK TAKİP SİSTEMİ</title>
</head>
<body>
<br><br><hr>
<div class="container table-bordered" id="cont">

<!-- ÜST BÖLÜM -->
<div class="row border-bottom" style="min-height:60px;padding:15px 25px">
		<div class="col-md-3" id="ustbolum"><h4>Hoşgeldin, <?php echo @$_COOKIE["kulad"]; ?></h4></div>
		<div class="col-md-9" id="ustbolum"><ul class="nav justify-content-end" >

			<?php

				$app->loginAuth(@$_COOKIE["kulsifre"], @md5(@$_COOKIE["kulad"]));

			 ?>

</ul></div>
</div>

<?php

	switch(@$_GET["status"])
	{
		case "Login":
			 $app->Login($_POST["kulad"],$_POST["kulsifre"]);
			 break;
	}

	if(@$_COOKIE["kulad"] == "" and @$_COOKIE["kulsifre"] == "")
	{

 ?>
<br><br><hr>
 <div class="col-md-12 " id="kutuliste"><br>
 		<div class="row" style="text-align:center">
 		<div class="col-md-12" >Giriş Paneli</div>
 				<div class="col-md-12"> <br>
 					<form action="index.php?status=Login" method="Post">
 						Kullanıcı adı : <br>	<input name="kulad" type="text" /><br>
 						Şifre : <br> <input name="kulsifre" type="password" /><br>
 					 <br> <input name="giris" type="submit" class="btn btn-outline-success col-md-3" value="Giriş" style="margin-bottom:3px" />
 				 </form> <br>
				 <div class="col-md-12" >
					 <small>Yetki 1 : Kullanıcı adı "mert" şifre de "123" dür</small> <br>
					 <small>Yetki 2 : Kullanıcı adı "mert2" şifre de "123" dür</small> 
				 </div>
 				</div>
 		</div>
 </div>
<hr>
<?php }else { ?>
<!-- ÜST BÖLÜM -->

<!-- KATEGORİ BÖLÜM -->
<div class="row border-bottom" style="padding:0px 25px">
		<div class="col-md-9"><br />
            <?php
                $app->categorySelect();
            ?>
        </div>
		<div class="col-md-3"  style="line-height:3;">
			<form class="form-inline" action="index.php?status=tableDesignChange" method="post">
  <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Listeleme</label>
  <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="tercih">
		<?php
			$returned = $app->tableDesign(@$_COOKIE["kulsifre"], @md5(@$_COOKIE["kulad"]));

			if($returned == 0)
			{
				echo '
				<option value="0" selected >Kutu</option>
				<option value="1">Liste</option>
				';
			}
			elseif ($returned == 1)
			{
				echo '
				<option value="0">Kutu</option>
				<option value="1" selected >Liste</option>
				';
			}
		?>
     </select>
  <input type="submit" class="btn btn-primary my-1" name="tableDesignButton" value="Uygula" />
</form></div>
</div>
<!-- KATEGORİ BÖLÜM -->
<br>
<!-- ORTA BÖLÜM -->
<div class="row" style="padding:0px 35px">

    <?php
        @$status = $_GET["status"];

        switch($status)
        {
             case "category":
								$app->categoryProductsSelect($_GET["id"]);
                break;
							case "productUpdate":
							  $app->productUpdate();
		             break;
							case "productUpdateSelected":
	 							 $app->productUpdateSelected($_GET["id"],$_POST["urunStok"]);
	 		           break;
							case "passChange":
	 	 						 $app->passChange(@$_POST["eskisifre"],@$_POST["yenisifre"]);
	 	 		         break;
							case "exit":
								 $app->exit();
								 break;
							case "tableDesignChange":
	 							 $app->tableDesignChange();
	 							 break;
							case "process":
	 	 						 $app->process();
	 	 						 break;
						  case "categoryAdd":
								 $app->categoryAdd();
	 	 	 					 break;
							case "productSelect":
	 							 $app->productSelect();
	 	 	 	 				 break;
							case "productDelete":
	 	 						 $app->productDelete(@$_GET["id"]);
	 	 	 	 	 			 break;
							case "categorySelectProcess":
	 	 	 					 $app->categorySelectProcess();
	 	 	 	 	 	 		 break;
							case "categorySelectProcessDelete":
	 	 	 	 				 $app->categorySelectProcessDelete(@$_GET["id"]);
	 	 	 	 	 	 	 	break;
							case "productAdd":
		 	 	 	 			 $app->productAdd();
		 	 	 	 	 	 	 break;
							case "userRequests":
	 		 	 	 	 		 $app->userRequests();
	 		 	 	 	 	 	 break;
							case "userRequestsSet":
 		 	 					 $app->userRequestsSet(@$_GET["id"]);
 		 	 	 	 	 			 break;
							case "productReport":
		  					 $app->productReport(@$_GET["id"]);
		 	 	 	 	 			 break;

            default:
                $app->defaultProductsSelect();
        }
    ?>

   <!-- KUTU İÇERİĞİ BÖLÜMÜ -->




</div>
<!-- ORTA BÖLÜM -->






</div>

<div class="container table-bordered table-info text-center">
<!-- SAYFALAMA BÖLÜM -->
<div class="row" style="min-height:30px;">
		<div class="col-md-12">SAYFALAMA</div>
</div>
<!-- SAYFALAMA BÖLÜM -->
</div>
<?php } ?>
<hr>
</body>

</html>
