<?php


    class Stok
    {
        public $db,$userId,$tableDesign;

        function __construct()
        {
            try{
                $this->db = new PDO("mysql:host=localhost;dbname=stoktakip;charset=utf8","root","123456789");
            }
            catch(PDOException $err)
            {
                die($err->getMessage());
            }
        }

        function dbQuery($query)
        {
          $querySelect = $this->db->prepare($query);
          $querySelect->execute();
          return $querySelect;
        }

        function categorySelect()
        {
            $querySelect = $this->dbQuery("SELECT * FROM kategori");

            foreach($querySelect->fetchAll(PDO::FETCH_ASSOC) as $var)
            {
                echo '<a id="link" href="index.php?status=category&id='.$var["id"].'" style="padding-top:40px;">'.$var["ad"].'</a> | ';
            }
        }

        function defaultProductsSelect()
        {
            $querySelect = $this->dbQuery("SELECT * FROM urunler LIMIT 3");

            if($this->tableDesign == 0)
            {
              foreach($querySelect->fetchAll(PDO::FETCH_ASSOC) as $var)
              {
                  echo '

                      <div class="col-md-1 table-bordered " id="kutuliste">
                          <div class="row" style="text-align:center">
                          <div class="col-md-12" ><u>'.$var["ad"].'</u></div>
                              <div class="col-md-12">Stok: '.$var["stok"].'</div>
                              <div class="col-md-12">
                                <form action="index.php?status=productUpdate" method="Post">
                                <input name="urunid" type="hidden" value="'.$var["id"].'" />
                                <input name="guncel" type="submit" class="btn btn-outline-success" value=">" style="margin-bottom:3px" />
                                </form>
                              </div>
                          </div>
                      </div>
                  ';
              }
            }
            else
            {
              foreach($querySelect->fetchAll(PDO::FETCH_ASSOC) as $var)
              {
                  echo '
                    <table class="table table-hover text-center">
                        <thead>
                          <tr>
                            <th>Ürün Adı</th>
                            <th>Stok Durumu</th>
                            <th>İşlem</th>
                          </tr>
                        </thead>

                        <tbody>

                          <tr>
                            <td>'.$var["ad"].'</td>
                            <td><b class="text-danger">'.$var["stok"].'</b></td>
                            <td>
                              <form action="index.php?status=productUpdate" method="Post">
                              <input name="urunid" type="hidden" value="'.$var["id"].'" />
                              <input name="guncel" type="submit" class="btn btn-outline-success" value=">" style="margin-bottom:3px" />
                              </form>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                  ';
              }
            }
        }

        function categoryProductsSelect($categoryId)
        {
            $querySelect = $this->dbQuery("SELECT * FROM urunler WHERE katid=".$categoryId."");

            if($querySelect->RowCount() == 0)
            {
              echo "<div class=' col-md-12 alert alert-danger'><center>Seçilen kategoriye ait ürün bulunamadı !!</center></div>";
            }
            else
            {
              if($this->tableDesign == 0)
              {
                foreach($querySelect->fetchAll(PDO::FETCH_ASSOC) as $var)
                {
                    echo '

                        <div class="col-md-1 table-bordered " id="kutuliste">
                            <div class="row" style="text-align:center">
                            <div class="col-md-12" ><u>'.$var["ad"].'</u></div>
                                <div class="col-md-12">Stok: '.$var["stok"].'</div>
                                <div class="col-md-12">
                                  <form action="index.php?status=productUpdate" method="Post">
                                  <input name="urunid" type="hidden" value="'.$var["id"].'" />
                                  <input name="guncel" type="submit" class="btn btn-outline-success" value=">" style="margin-bottom:3px" />
                                  </form>
                                </div>
                            </div>
                        </div>
                    ';
                }
              }
              else
              {
                foreach($querySelect->fetchAll(PDO::FETCH_ASSOC) as $var)
                {
                    echo '
                      <table class="table table-hover text-center">
                          <thead>
                            <tr>
                              <th>Ürün Adı</th>
                              <th>Stok Durumu</th>
                              <th>İşlem</th>
                            </tr>
                          </thead>

                          <tbody>

                            <tr>
                              <td>'.$var["ad"].'</td>
                              <td><b class="text-danger">'.$var["stok"].'</b></td>
                              <td>
                                <form action="index.php?status=productUpdate" method="Post">
                                <input name="urunid" type="hidden" value="'.$var["id"].'" />
                                <input name="guncel" type="submit" class="btn btn-outline-success" value=">" style="margin-bottom:3px" />
                                </form>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                    ';
                }
              }
            }

        }

        function productUpdate()
        {
          @$buton = $_POST["guncel"];
          @$urunid = $_POST["urunid"];

          if($buton)
          {
            $querySelect = $this->dbQuery("SELECT * FROM urunler WHERE id=".$urunid."");

            foreach($querySelect as $var)
            {
              echo'<div class="col-md-12 table-bordered" id="kutuliste" style="padding:120px 0px">
                  <div class="row" style="text-align:center">
                  <div class="col-md-12" ><u>'.$var["ad"].'</u></div>
                      <div class="col-md-12"> <br>
                        <form action="index.php?status=productUpdateSelected&id='.$var["id"].'" method="Post">
                        STOK : <input name="urunStok" type="text" value="'.$var["stok"].'" /> <br> <br>
                        <input name="giris" type="submit" class="btn btn-outline-success col-md-4" style="margin-bottom:3px" />
                        </form>
                      </div>
                  </div>
              </div>';
            }
          }
          else
          {
            echo "Hata var !";
          }
        }

        function productUpdateSelected($productId,$productValue)
        {
          $querySelect = $this->dbQuery("UPDATE urunler SET stok=".$productValue." WHERE id=".$productId);

          if($querySelect -> rowCount() > 0)
          {
            echo "<div class=' col-md-12 alert alert-success'><center>Stok başarıyla güncellendi !!</center></div>";
          }
          else
          {
            echo "<div class=' col-md-12 alert alert-danger'><center>Stok Güncellenemedi !!</center></div>";
          }
        }

        function Login($kulad,$kulsifre)
        {
          $kuladmd5 = md5($kulad);
          $kulsifremd5 = md5($kulsifre);

          $login = "SELECT * FROM kullanici WHERE kulad='{$kuladmd5}' AND kulsifre='{$kulsifremd5}'";
          $loginControl = $this->db->prepare($login);
          $loginControl->execute();

          if($loginControl -> rowCount())
          {
            setcookie("kulad", $kulad, time() + 60*60*24);
            setcookie("kulsifre", $kulsifremd5, time() + 60*60*24);
            echo "<div class=' col-md-12 alert alert-success'><center>Giriş Başarılı. Yönlendiriliyorsunuz...</center></div>";
            header("refresh:3, url=index.php");
          }
          else
          {
            echo "<div class=' col-md-12 alert alert-danger'><center>Giriş başarısız</center></div>";
            header("refresh:3, url=index.php");
          }
        }

        function loginAuth($userPass,$userName)
        {

          $querySelect = $this->dbQuery("SELECT * FROM kullanici WHERE kulsifre='{$userPass}' and kulad='{$userName}'");
          $variables = $querySelect->fetch(PDO::FETCH_ASSOC);

          @$this->userId = $variables["id"];
          @$this->tableDesign = $variables["tercih"];


          if(@$variables["yetki"] == 1)
          {
            echo '
            <li class="nav-item"  id="islem">
              <a class="nav-link btn btn-outline-dark" href="index.php">Anasayfa</a>
            </li>
            <li class="nav-item"  id="islem">
              <a class="nav-link btn btn-outline-dark disabled" href="index.php?status=process">İşlemler</a>
            </li>
            <li class="nav-item" id="islem">
              <a class="nav-link btn btn-outline-dark" href="index.php?status=passChange">Şifre Değiştir</a>
            </li>
            <li class="nav-item" id="islem">
              <a class="nav-link btn btn-outline-dark"  href="index.php?status=exit">Çıkış</a>
            </li>';
          }
          else
          {
            echo '
            <li class="nav-item"  id="islem">
              <a class="nav-link btn btn-outline-dark" href="index.php">Anasayfa</a>
            </li>
            <li class="nav-item"  id="islem">
              <a class="nav-link btn btn-outline-dark" href="index.php?status=process">İşlemler</a>
            </li>
            <li class="nav-item" id="islem">
              <a class="nav-link btn btn-outline-dark" href="index.php?status=passChange">Şifre Değiştir</a>
            </li>
            <li class="nav-item" id="islem">
              <a class="nav-link btn btn-outline-dark"  href="index.php?status=exit">Çıkış</a>
            </li>';
          }

        }

        function exit()
        {
          setcookie("kulad", $_COOKIE["kulad"], time() - 10 );
          setcookie("kulsifre", $_COOKIE["kulsifre"], time() - 10 );
          header("refresh:2, url=index.php");
        }

        function passChange($eskisifre=false,$yenisifre=false)
        {
          $eskisifremd5 = md5($eskisifre);
          $yenisifremd5 = md5($yenisifre);
          if(@$_POST)
          {
            $querySelect = $this->dbQuery("SELECT * FROM kullanici WHERE kulsifre='{$eskisifremd5}' and id='{$this->userId}'");

            if($querySelect->rowCount() > 0)
            {
              $newqueryUpdate = $this->dbQuery("UPDATE kullanici SET kulsifre='{$yenisifremd5}' WHERE id='{$this->userId}'");

              if($newqueryUpdate->rowCount() > 0)
              {
                echo "<div class='col-md-12 alert alert-success'><center>Şifre Güncelleme İşlemi Başarılı ! Yeniden Giriş Yapınız...</center></div>";
                self::exit();

              }
              else
              {
                echo "<div class='col-md-12 alert alert-danger'><center>Şifreniz Güncellenemedi !</center></div>";
              }
            }
            else
            {
              echo "<div class='col-md-12 alert alert-danger'><center>Mevcut Şifre Yanlış !</center></div>";
            }
          }
          else
          {
            echo '<div class="col-md-12 " id="kutuliste"><br>
            		<div class="row" style="text-align:center">
            		<div class="col-md-12" ><h3>Şifre Değiştir</h3></div>
            				<div class="col-md-12"> <br>
            					<form action="index.php?status=passChange" method="Post">
            						Mevcut Şifre : <br>	<input name="eskisifre" type="password" /><br>
            						Yeni Şifre : <br> <input name="yenisifre" type="password" /><br>
            					 <br> <input name="ok" type="submit" class="btn btn-outline-success col-md-2" value="Değiştir" style="margin-bottom:3px" />
            				 </form> <br>
           				 <div class="col-md-12" ><small>Kullanıcı adı "mert" şifre de "123" dür</small>	</div>
            				</div>
            		</div>
            </div>';
          }
        }

        function tableDesign($userPass,$userName)
        {
          $querySelect = $this->dbQuery("SELECT * FROM kullanici WHERE kulsifre='{$userPass}' and kulad='{$userName}'");
          $variables = $querySelect->fetch(PDO::FETCH_ASSOC);
          return $variables["tercih"];
        }

        function tableDesignChange()
        {
          if(@$_POST["tableDesignButton"])
          {
            $querySelect = $this->dbQuery("UPDATE kullanici SET tercih='{$_POST['tercih']}' WHERE id='{$this->userId}'");

            if($querySelect->RowCount() > 0)
            {
              echo "<div class=' col-md-12 alert alert-success'><center>Listeleme tercihi güncellendi !!</center></div>";
              header("refresh:2, url=index.php");
            }
            else
            {
              echo "<div class=' col-md-12 alert alert-danger'><center>Listeleme tercihi güncellenemedi !</center></div>";
              header("refresh:2, url=index.php");
            }
          }
        }

        function process()
        {

          if($this->dbQuery("SELECT yetki FROM kullanici WHERE id='{$this->userId}'")->fetch(PDO::FETCH_ASSOC)["yetki"] == 2)
          {
            echo '
              <div class="col-md-4">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <a href="index.php?status=categoryAdd" class="btn btn-outline-secondary" style="width:90%;padding:10px;margin-top:10px">Kategori Ekle</a>
                    </div>
                    <div class="col-md-12">
                      <a href="index.php?status=categorySelectProcess" class="btn btn-outline-secondary" style="width:90%;padding:10px;margin-top:10px">Kategori Listele</a>
                    </div>
                    <div class="col-md-12">
                      <a href="index.php?status=productSelect" class="btn btn-outline-secondary" style="width:90%;padding:10px;margin-top:10px">Ürün Listele</a>
                    </div>
                    <div class="col-md-12">
                      <a href="index.php?status=productAdd" class="btn btn-outline-secondary" style="width:90%;padding:10px;margin-top:10px">Ürün Ekle</a>
                    </div>
                    <div class="col-md-12">
                      <a href="index.php?status=userRequests" class="btn btn-outline-secondary" style="width:90%;padding:10px;margin-top:10px">Stok Talepler
                      ';

                        $talepler = $this->dbQuery("SELECT COUNT(*) FROM talepler");

                      echo '&nbsp;<span class="badge badge-dark">'.$talepler->fetchColumn().'</span></a>
                    </div>
                    <div class="col-md-12">
                      <a href="index.php?status=productReport" class="btn btn-outline-secondary" style="width:90%;padding:10px;margin-top:10px">Ürün Raporu</a>
                    </div>
                  </div>
                </div>
              </div>
            ';
          }
          else
          {
            echo "<div class='col-md-12 alert alert-danger'><center>Gereken YETKİYE Sahip Değilsiniz !!</center></div>";
            header("refresh:3, url=index.php");
          }
        }

        function categoryAdd()
        {
          self::process();

          if(@$_POST["categoryAdd"])
          {
            $querySelect = $this->dbQuery("INSERT INTO kategori SET ad='{$_POST['kategoriad']}'");

            if($querySelect->rowCount() > 0)
            {
              echo "<div class='col-md-8 alert alert-success'><center style='line-height:17'>Kategori Başarıyla Eklendi!</center></div>";
            }
            else
            {
              echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>Kategori Eklenemedi!</center></div>";
            }
          }
          else
          {
            echo '
              <div class="col-md-8" style="background-color:#eee">
                 <div class="row">
                   <div class="col-md-12 " id="kutuliste" style="margin-top:50px"><br>
                      <div class="row" style="text-align:center">
                          <div class="col-md-12"> <br>
                            <form action="index.php?status=categoryAdd" method="Post">
                              Kategori Adı : <br> <br>	<input name="kategoriad" type="text" placeholder="Meşrubatlar.." /><br>
                             <br> <input name="categoryAdd" type="submit" class="btn btn-outline-success col-md-2" value="Ekle" style="margin-bottom:3px" />
                           </form>
                          </div>
                      </div>
                   </div>
                 </div>
              </div>
            ';
          }
        }

        function productSelect()
        {
          self::process();

          $querySelect = $this->dbQuery("SELECT * FROM urunler")->fetchAll(PDO::FETCH_ASSOC);

          echo '<div class="col-md-8">
          <div class="row" style="text-align:center">
          ';
          foreach ($querySelect as $var) {
            echo '

                  <div class="col-md-2 table-bordered " id="kutuliste">
                      <div class="col-md-12" ><u>'.$var["ad"].'</u></div>
                          <div class="col-md-12">'.$var["stok"].'</div>
                          <div class="col-md-12">
                            <form action="index.php?status=productDelete&id='.$var["id"].'" method="Post">
                            <input name="productDelete" type="submit" class="btn btn-outline-danger" value="SİL" style="margin-bottom:3px" />
                            </form>
                          </div>

                  </div>
            ';
          }
          echo '</div></div>';
        }

        function productDelete($productId)
        {
          self::process();

          $querySelect = $this->dbQuery("DELETE FROM urunler WHERE id='{$productId}'");

          if($querySelect->rowCount() > 0)
          {
            echo "<div class='col-md-8 alert alert-success'><center style='line-height:17'>Ürün başarıyla Silindi !</center></div>";
          }
          else
          {
            echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>Ürün Silinemedi !</center></div>";
          }
        }

        function categorySelectProcess()
        {
          self::process();

          $querySelect = $this->dbQuery("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);

          echo '<div class="col-md-8">
          <div class="row" style="text-align:center">
          ';
          foreach ($querySelect as $var) {
            echo '

                  <div class="col-md-12 table-bordered " id="kutuliste">
                          <div class="col-md-12">'.$var["ad"].'
                            <form action="index.php?status=categorySelectProcessDelete&id='.$var["id"].'" method="Post">
                            <input name="productDelete" type="submit" class="btn btn-outline-danger" value="SİL" style="margin-bottom:3px" />
                            </form>
                          </div>

                  </div>
            ';
          }
          echo '</div></div>';
        }

        function categorySelectProcessDelete($categoryId)
        {
          self::process();

          $querySelect = $this->dbQuery("DELETE FROM kategori WHERE id='{$categoryId}'");

          if($querySelect->rowCount() > 0)
          {
            echo "<div class='col-md-8 alert alert-success'><center style='line-height:17'>Kategori başarıyla Silindi !</center></div>";
          }
          else
          {
            echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>Kategori Silinemedi !</center></div>";
          }
        }

        function productAdd()
        {
          self::process();

          if(@$_POST["productAdd"])
          {
            $querySelect = $this->dbQuery("INSERT INTO urunler SET ad='{$_POST['urunad']}', stok='{$_POST['urunstok']}', katid='{$_POST['katid']}'");

            if($querySelect->rowCount() > 0)
            {
              echo "<div class='col-md-8 alert alert-success'><center style='line-height:17'>Ürün Başarıyla Eklendi!</center></div>";
            }
            else
            {
              echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>Ürün Eklenemedi!</center></div>";
            }
          }
          else
          {
            echo '
              <div class="col-md-8" style="background-color:#eee">
                 <div class="row">
                   <div class="col-md-12 " id="kutuliste" style="margin-top:-10px"><br>
                      <div class="row" style="text-align:center">
                          <div class="col-md-12"> <br>
                            <form action="index.php?status=productAdd" method="Post">
                              Ürün Adı : <br><input name="urunad" type="text" placeholder="Portakal.." /><br><br>
                              Ürün Stoğu : <br><input name="urunstok" type="text" placeholder="1000.." /><br><br>
                              Ürün Kategorisi : <br><select class="" name="katid">
                              ';

                              $categorySelect = $this->dbQuery("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
                              print_r($categorySelect);
                              foreach($categorySelect as $var)
                              {
                                echo '
                                  	<option value="'.$var["id"].'">'.$var["ad"].'</option>
                                ';
                              }

                              echo '</select> <br>
                             <br> <input name="productAdd" type="submit" class="btn btn-outline-success col-md-2" value="Ekle" style="margin-bottom:3px" />
                           </form>
                          </div>
                      </div>
                   </div>
                 </div>
              </div>
            ';
          }
        }

        function userRequests()
        {
          self::process();

          $querySelect = $this->dbQuery("SELECT * FROM talepler")->fetchAll(PDO::FETCH_ASSOC);

          echo '<div class="col-md-8">
          <div class="row" style="text-align:center">
          ';
          foreach ($querySelect as $var) {
            echo '

                  <div class="col-md-5 table-bordered " id="kutuliste">
                      <div class="col-md-12" ><u>'.$var["urunad"].'</u></div>
                          <div class="col-md-12">'.$var["talepstok"].'</div>
                          <div class="col-md-12">
                            <form action="index.php?status=userRequestsSet&id='.$var["id"].'" method="Post">
                            <input name="userRequestsConfirm" type="submit" class="btn btn-outline-success" value="✓" style="margin-bottom:3px" />
                            <input name="userRequestsDelete" type="submit" class="btn btn-outline-danger" value="X" style="margin-bottom:3px" />
                            </form>
                          </div>

                  </div>
            ';
          }
          echo '</div></div>';
        }

        function userRequestsSet($requestId)
        {
          self::process();

          if(@$_POST["userRequestsDelete"])
          {
            $querySelect = $this->dbQuery("DELETE FROM talepler WHERE id='{$requestId}'");

            if($querySelect->rowCount() > 0)
            {
              echo "<div class='col-md-8 alert alert-success'><center style='line-height:17'>Stok Talebi başarıyla Silindi !</center></div>";
            }
            else
            {
              echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>Stok Talebi Silinemedi !</center></div>";
            }
          }
          elseif(@$_POST["userRequestsConfirm"])
          {
            $requestSelect = $this->dbQuery("SELECT * FROM talepler WHERE id='{$requestId}'")->fetch(PDO::FETCH_ASSOC);
            $productSelect = $this->dbQuery("SELECT * FROM urunler WHERE id='{$requestSelect['urunid']}'")->fetch(PDO::FETCH_ASSOC);

            $productStokNew = $productSelect["stok"] + $requestSelect["talepstok"];

            $productUpdate = $this->dbQuery("UPDATE urunler SET stok='{$productStokNew}' WHERE id='{$requestSelect['urunid']}'");
            $requestDelete = $this->dbQuery("DELETE FROM talepler WHERE id='{$requestId}'");

            if( ($productUpdate->rowCount() > 0) AND ($requestDelete->rowCount() > 0))
            {
              echo "<div class='col-md-8 alert alert-success'><center style='line-height:17'>Stok Talebi başarıyla Güncellendi !</center></div>";
            }
            else
            {
              echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>Stok Talebi Güncellenemedi !</center></div>";
            }

          }
          else
          {
            echo "<div class='col-md-8 alert alert-danger'><center style='line-height:17'>İşlem Bulunamadı !</center></div>";
          }
        }

        function productReport()
        {
          self::process();

          $toplamProduct = $this->dbQuery("SELECT COUNT(*) FROM urunler")->fetchColumn();
          $toplamStok = $this->dbQuery("SELECT SUM(stok) FROM urunler")->fetchColumn();
          $toplamTalep = $this->dbQuery("SELECT COUNT(*) FROM talepler")->fetchColumn();
          $toplamKullanici = $this->dbQuery("SELECT COUNT(*) FROM kullanici")->fetchColumn();
          $toplamKategori = $this->dbQuery("SELECT COUNT(*) FROM kategori")->fetchColumn();

          echo '
          <div class="col-md-8">
            <div class="row" style="text-align:center">
                <div class="col-md-12 table-bordered  btn-secondary" id="kutuliste" style="padding:10px">
                   <div class="col-md-12" ><u>Toplam Stok Sayısı</u></div>
                     <div class="col-md-12">
                       '.$toplamStok.'
                      </div>
                </div>

                <div class="col-md-12 table-bordered  btn-secondary" id="kutuliste" style="padding:10px">
                   <div class="col-md-12" ><u>Toplam Ürün Sayısı</u></div>
                     <div class="col-md-12">
                       '.$toplamProduct.'
                      </div>
                </div>

                <div class="col-md-12 table-bordered  btn-secondary" id="kutuliste" style="padding:10px">
                   <div class="col-md-12" ><u>Toplam Talep Sayısı</u></div>
                     <div class="col-md-12">
                       '.$toplamTalep.'
                      </div>
                </div>

                <div class="col-md-12 table-bordered  btn-secondary" id="kutuliste" style="padding:10px">
                   <div class="col-md-12" ><u>Aktif Kullanıcı Sayısı</u></div>
                     <div class="col-md-12">
                       '.$toplamKullanici.'
                      </div>
                </div>

                <div class="col-md-12 table-bordered  btn-secondary" id="kutuliste" style="padding:10px">
                   <div class="col-md-12" ><u>Toplam Kategori Sayısı</u></div>
                     <div class="col-md-12">
                       '.$toplamKategori.'
                      </div>
                </div>
            </div>
          </div>

          ';

        }

    }



?>
