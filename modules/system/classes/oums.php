<?php

/* 
 * Sistemin kullandigi tum php classlari bu bolumde saklanir.
 *
 */

class system {
     /** Tablo ismi ve id yi kullanarak kayıtları siler.
     *
     * @param type $tablo verinin silinceği tablonun adı
     * @param type $id silinmesi istenen verinin idsi.
     */
    Private function sil($tablo, $id) {
        global $conn;
        $sqlinsert = "DELETE FROM $tablo WHERE id='$id';";
        if ($conn->query($sqlinsert) === TRUE) {
            return "ok";
        } else {
            echo "Error: " . $sqlinsert . "<br>" . $conn->error;
            return $mysqli->error;
        }
    }

    /** text alanından gelebilecek olan akışı bozan karakterleri düzeltir
     *
     * @param type $not formdan gelen değer
     * @return type değişken olarak tanımlayınız.
     */
    private function duzelt($not) {
        $not = str_replace("'", "\"", $not);
        mysql_real_escape_string($not);
        return $not;
    }

    /** Guncelle fonksiyonu gelen verilerin istenen kayıtta otomatik olarak güncellenmesine olanak tanır form fieldleri tablo kolon isimleri ile aynı olmalıdır.
     * @kullanımı guncelle($post,tablo,id) şeklindedir. Firma kayıt güncellemesinde değişiklik tarihi otomatik olarak eklenmektedir.
     *
     * @param type $post $_POST veya $_GET yazılmılıdır.
     * @param type $tablo tablo adını belirtir
     * @param type $id değişikllk yapılacak kaydın id'si.
     * @param $adres . işlem bitince gidielcek sayfa adresi
     */
    private function sqlupdate($post, $tablo, $id) {
        global $conn;
        $sorgu = "";
        foreach ($post as $key => $value) {
            if ($key != "id") {
                $sorgu .= "`".$key."`". "=" . "'" . $value . "' ,";
            }
        }
        $sorgu = substr($sorgu, 0, -1);
        $sqlinsert = "UPDATE $tablo SET $sorgu WHERE `id`=$id";
        if ($conn->query($sqlinsert) === TRUE) {
            return "ok";
           
        } else {

            return $mysqli->error;
        }
    }

    /**
     * sql insert mysql e veri kaydı için kullanılır form fieldleri tablo kolon isimleri ile aynı olmalıdır.
     * @dikkat islem isimli form verisi işleme dahil edilmez.
     * @Kullanımı sqlinsert($post,"tablo adı", "işlem bitince gidilecek sayfa") şeklindedir
     * @param type $post $_POST veya $_GET yazılmılıdır.
     * @param type $tablo tablo adını belirtir
     * @param $adres . işlem bitince gidilecek sayfa adresi
     */
    Private function sqlinsert($post, $tablo) {
        global $conn;
        $table = 1;
        foreach ($post as $key => $value) {
            if ($key != "islem") {
                if ($table == 1) {
                    $table = "`".$key."`";
                    $row = "'" . $value . "'";
                } else {
                    $table = $table . " , " . "`".$key."`";
                    $row = $row . "," . "'" . $value . "'";
                }
            }
        }
        $sqlinsert = "INSERT INTO $tablo ($table) Values ($row)";
        if ($conn->query($sqlinsert) === TRUE) {
            return "ok";
        } else {
            echo "Error: " . $sqlinsert . "<br>" . $conn->error;
  
        }
    }

    /**
     * formlarla gelen verilerin sql update, insert ve delete işlemlerini yaptırır
     * @param type $post = formdan gelen post verisi
     * @param type $tablo = işlem yapılacak tablonun adı
     * @param type $adres = işlem bittiğinde dönülecek adres.
     * @return strıng işlem başarı ile gerçekleşirse sonu ok olarak döner .
     */
    public function islem($post, $tablo, $adres) {
        if (isset($post['id'])) {
            $id = $post['id'];
        } else {
            $id = 0;
        }
          if (isset($post['sil'])) {
                $id = $post['sil'];
                $sonuc = $this->sil($tablo, $id);
                if ($sonuc == "ok") {
                    return "ok";
                } else {
                    echo $sonuc;
                }
            } else {

        if ($id > 0) {

                $sonuc = $this->sqlupdate($post, $tablo, $id, $adres);

                if ($sonuc == "ok") {
                    return $sonuc;
                }
           
        } else {

            $sonuc = $this->sqlinsert($post, $tablo);
            if ($sonuc == "ok") {
                return "ok";
            }
        }
    }}
    /**
     * sql sorgusu için kullanılır dışarından çağırılamaz.
     * @param type $tablo ( verinin getirileceği tablonun adı)
     * @param type $lang ( çağırılacak olan verinin dili "tr", "en" gibi. ) 
     * @param type $sorgu ( sorgu sonucunda gönderilecek olan kolonun adı )
     * @return type
     */
    private function mysql($tablo, $lang, $sorgu) {
        global $conn;
        $query_db = "SELECT * FROM $tablo where Dil = '$lang'";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        return $row_db[$sorgu];
    }

    /**
     * Mysql üzerinden tek bir sonuç çağırmak için kullanılacak olan komuttur
     * @param type $sorgu (select * fromdan sonrasında istenilen sorgu yazılabilir )
     * @param type $kolon ( sonucu getirilecek olan kolonun adı )
     */
    public function sqlsorgu($sorgu, $kolon) {
        global $conn;
        $query_db = "SELECT * FROM $sorgu ";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        return $row_db[$kolon];
    }
      /**
     * Mysql üzerinden Toplama islemi yapmak için kullanılacak olan komuttur
     * @param type $sorgu (select * fromdan sonrasında istenilen sorgu yazılabilir )
     * @param type $kolon ( toplami getirilecek olan kolonun adı )
     */
    public function sqlsum($sorgu, $kolon) {
        global $conn;
        $query_db = "SELECT Sum($kolon) as summ FROM $sorgu ";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        $totalRows_db = mysqli_num_rows($db);
        if ($totalRows_db > 0){
        return $row_db['summ'];
        }else{
           return 0.00; 
        }
    }
    

    /**
     * Sql de birşeyin var olup olmadığını kontrol etmek için kullanılır sonuç kayıt sayısıdır.
     * @param type $sorgu ( sql sorgusunun "select * from" dan sonraki bölümü yazılır)
     * @return kayıt sayısı
     */
    public function check($sorgu) {
        global $conn;
        $query_db = "SELECT * FROM $sorgu ";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $totalRows_db = mysqli_num_rows($db);
        return $totalRows_db;
    }
    
    /**
     * Tarih verilerini sql den okunabilir hale getirir.
     * 
     */
     function tarih($date, $lang) {
        if (empty($date)) {
            echo "Tarih girilmemiş";
        } else {
            if ($lang == "tr") {
                setlocale(LC_TIME, "turkish");
                $date = iconv('latin5', 'utf-8', strftime("%d %B %Y - %A", strtotime($date)));
            } else {
                setlocale(LC_TIME, "English_United_Kingdom");
                $date = iconv('latin5', 'utf-8', strftime(" %B %d %Y - %A", strtotime($date)));
            }
            return $date;
        }
    }
    
       /**
     * Yazilarda kelime sayisi girilerek istenen sayida kelime gosterilmesini saglar.
     * 
     */
       function limit_words($string, $word_limit) {
        $string = strip_tags($string);
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }
    
    
    /**
     * SQL sorgulaması gerektiren işlemlerle class için de kullanılır.
     * @param type $tablo = verinin getireleceği tablo adı.
     * @param type $id = sorgulanacak verinin id'si
     * @param type $sqlname = kolon adı
     * @return type =veri değişken olarak gönderilir.
     */
    private function sql($tablo, $id, $sqlname) {
        global $conn;
        $query_db = "SELECT * FROM $tablo where id = $id";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        return $row_db[$sqlname];
    }

    /**
     * Text field çizdirmek ve varsa verinin içine eyazılmasını sağlar.
     * @param type $baslik = Text boxun label alanına yazılacak olan değer.
     * @param type $sqlname = verinin gönderileceği kolonun adı( name= değişkenine atanır ).
     * @param type $extra = required veya herhangi bir script ile bağ kurmak isterse bu değişkene tanımlanır.
     * @param type $tablo = Güncelleme işlemleri için verinin getireleceği tablonun adı.
     * @param type $id = Güncelleme işlemleri için verinin getireleceği kaydın id'si.
     */
    function text($baslik, $sqlname, $extra, $class, $tablo, $id) {
        if ($id > 0) {
            $deger = $this->sql($tablo, $id, $sqlname);
        } else {
            $deger = "$id";
        }
        if (!empty($baslik)){
        echo "<label>" . $baslik . "</label>";
        }
        echo ' <input name="' . $sqlname . '" type="text" class="form-control ' . $class . '" value="' . $deger . '"' . $extra . '>';
    }
 
    
      function time($baslik, $sqlname, $extra, $class, $tablo, $id) {
        if ($id > 0) {
            $deger = $this->sql($tablo, $id, $sqlname);
        } else {
            $deger = "$id";
        }
        if (!empty($baslik)){
        echo "<label>" . $baslik . "</label>";
        }
        echo ' <input name="' . $sqlname . '" type="time" class="form-control ' . $class . '" value="' . $deger . '"' . $extra . '>';
    }
    
        function number($baslik, $sqlname, $extra, $class, $tablo, $id) {
        if ($id > 0) {
            $deger = $this->sql($tablo, $id, $sqlname);
        } else {
            $deger = "$id";
        }
        if (!empty($baslik)){
        echo "<label>" . $baslik . "</label>";
        }
        echo ' <input name="' . $sqlname . '" type="number" class="form-control ' . $class . '" value="' . $deger . '"' . $extra . '>';
    }
    
        /**
     * Multi text field çizdirmek ve varsa verinin içine eyazılmasını sağlar.
     * @param type $baslik = Text boxun label alanına yazılacak olan değer.
     * @param type $sqlname = verinin gönderileceği kolonun adı( name= değişkenine atanır ).
     * @param type $extra = required veya herhangi bir script ile bağ kurmak isterse bu değişkene tanımlanır.
     * @param type $tablo = Güncelleme işlemleri için verinin getireleceği tablonun adı.
     * @param type $id = Güncelleme işlemleri için verinin getireleceği kaydın id'si.
     */
    
      
        function multi($baslik, $sqlname, $extra, $class, $tablo, $id) {
        if ($id > 0) {
            $deger = $this->sql($tablo, $id, $sqlname);
        } else {
            $deger = "$id";
        }
        if (!empty($baslik)){
        echo "<label>" . $baslik . "</label>";
        }
        echo ' <textarea name="' . $sqlname . '"  class="form-control ' . $class . '"'. $extra . '>'. htmlspecialchars($deger).'</textarea>';
    }
    
    function hidden($sqlname, $extra, $class, $deger) {
        echo ' <input name="' . $sqlname . '" type="hidden" class="form-control ' . $class . '" value="' . $deger . '"' . $extra . '>';
    }
    
        /**
     * Date input çizdirmek ve varsa verinin içine yazılmasını sağlar.
     * @param type $baslik = Text boxun label alanına yazılacak olan değer.
     * @param type $sqlname = verinin gönderileceği kolonun adı( name= değişkenine atanır ).
     * @param type $extra = required veya herhangi bir script ile bağ kurmak isterse bu değişkene tanımlanır.
     * @param type $tablo = Güncelleme işlemleri için verinin getireleceği tablonun adı.
     * @param type $id = Güncelleme işlemleri için verinin getireleceği kaydın id'si.
     */
    function date($baslik, $sqlname, $extra, $class, $tablo, $id) {
        if ($id > 0) {
            $deger = $this->sql($tablo, $id, $sqlname);
        } else {
            $deger = "";
        }
        echo "<label>" . $baslik . "</label>";
        echo ' <input name="' . $sqlname . '" type="date" class="form-control ' . $class . '" value="' . $deger . '"' . $extra . '>';
    }
    
        /**
     * Klasör listelemek ve select list şeklinde yazdırmak için kullanılır.
     *
     * @param type $baslik = label alanına yazılacak olan değer.
     * @param type $sqlname = verinin gönderileceği kolonun adı( name= değişkenine atanır ).
     * @param type $directory = kalsör listesi alınacak olan ana klasörün bulunduğu path.
     * @param type $tablo = Güncelleme için verinin çekileceği ana tablo adı
     * @param type $id = güncellemede seçilmiş olarak gelmesi gereken değerin bakılacağı kayıt id'si
     * @param type $extra = select box'a verilecekek işlevler için tanımlanır, "required v.b. )
     * @param type $class = özel bir CSS clası tanımlanabilir.

     */
    function directorylist($baslik, $sqlname, $directory, $tablo, $id, $extra, $class) {
        global $conn;
        echo "<label>" . $baslik . "</label>";
        echo ' <select  name="' . $sqlname . '" class="form-control ' . $class . '" ' . $extra . '>';
        echo "<option value='' >Seçiniz</option>";

//get all image files with a .jpg extension.
        $images = glob($directory . "*/");
        $uzunluk = strlen($directory);
//print each file name
        foreach ($images as $image) {
            $klasor1 = substr($image, $uzunluk, 180);
            $klasor = substr($klasor1, 0, -1);
            if ($id>0){
            $deger = $this->sql($tablo, $id, $sqlname);
            }else { $deger ="" ;}
            if ($deger == $klasor) {
                $select = "selected";
            } else {
                $select = "";
            }
            echo '<option value="' . $klasor . '"' . $select . '>' . $klasor . '</option>';
        };

        echo "</select>";
    }
    
      /**
     * Text field çizdirmek ve varsa verinin içine eyazılmasını sağlar.
     * @param type $baslik = Text boxun label alanına yazılacak olan değer.
     * @param type $sqlname = verinin gönderileceği kolonun adı( name= değişkenine atanır ).
     * @param type $extra = required veya herhangi bir script ile bağ kurmak isterse bu değişkene tanımlanır.
     * @param type $tablo = Güncelleme işlemleri için verinin getireleceği tablonun adı.
     * @param type $id = Güncelleme işlemleri için verinin getireleceği kaydın id'si.
     */
    function checkbox($baslik, $sqlname, $class, $tablo, $id) {
if($id > 0){
        $deger = $this->sql($tablo, $id, $sqlname);
} else { 
    $deger = "";
}
        if ($deger == 1) {
            $check = 'checked = "checked"';
        } else {
            $check = "";
        }
        echo '<div class="form-group chk" > <label>';
        echo ' <span style="margin-top: 20px;"><input name="' . $sqlname . '" type="checkbox" class="' . $class . '" value="1" ' . $check . '> ' . $baslik . "</label></span></div>";
    }
 
    
    
        public function sqlarray($tablo, $where) {
        global $conn;
        $query_db = "SELECT * FROM $tablo $where";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        return $row_db;
    }
    
        /**
     * Database bağlantılı "Select List" çizdirmek ve listeleme yapmak için
     *
     * @param type $baslik = label alanına yazılacak olan değer.
     * @param type $sqlname = verinin gönderileceği kolonun adı( name= değişkenine atanır ).
     * @param type $kaynaktablo = list verisinin getireleceği tablonun adı.
     * @param type $kolonid = Value olarak getirilecek değerin kolon adı
     * @param type $kolonvalue = listede görüntülenecek verinin kolon adı.
     * @param type $kaynakkolon = Güncelleme için otomatik seçimde kullanıcak olan ana verinin kolon adı (
     * @param type $tablo = Güncelleme için verinin çekileceği ana tablo adı ( sayfada tanımlanır )
     * @param type $id = güncellemede gösterilecek olan verinin id'si ( get ile alınır )
     */
    function selectlist($baslik, $sqlname, $kaynaktablo, $kolonid, $kolonvalue, $extra, $class, $kaynakkolon, $tablo, $id) {
        global $conn;
        global $langini;
        echo "<label>" . $baslik . "</label>";
        echo ' <select  name="' . $sqlname . '" class="form-control ' . $class . '" ' . $extra . '>';
        echo "<option value='' >Select</option>";
         if ($kaynaktablo == "kategori"){
             $katsorgu = "where Top_Cat is null";
         }else {
             $katsorgu = "";
         }
         
        $result = mysqli_query($conn, "SELECT * FROM $kaynaktablo $katsorgu order by $kolonvalue ASC ");
        while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
            $select = "";
            if ($id > 0) {
                $ids = $this->sql($tablo, $id, $sqlname);

                if ($ids == $row[$kolonid]) {
                    $select = "selected";
                }
            } else {
                if ($id == $row[$kolonid]) {
                    $select = "selected";
                }
            }
            echo "<option $select value='" . $row[$kolonid] . "'>" . $row[$kolonvalue] . "</option>";
            if ($kaynaktablo == "kategori"){
                
                $kategori = $row[$kolonid];
         
            $result1 = mysqli_query($conn, "SELECT * FROM kategori where Top_Cat = '$kategori' ");
            while (($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) != NULL) {
             
                  $select = "";
            if ($id > 0) {
                $ids = $this->sql($tablo, $id, $sqlname);

                if ($ids == $row1[$kolonid]) {
                    $select = "selected";
                }
            } else {
                if ($id == $row1[$kolonid]) {
                    $select = "selected";
                }
            }
echo "<option $select value='" . $row1[$kolonid] . "'>=>" . $row1[$kolonvalue] . "</option>";
          
  $kategori = $row1[$kolonid];
         
            $result2 = mysqli_query($conn, "SELECT * FROM kategori where Top_Cat = '$kategori' ");
            while (($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) != NULL) {
             
                  $select = "";
            if ($id > 0) {
                $ids = $this->sql($tablo, $id, $sqlname);

                if ($ids == $row2[$kolonid]) {
                    $select = "selected";
                }
            } else {
                if ($id == $row2[$kolonid]) {
                    $select = "selected";
                }
            }
echo "<option $select value='" . $row2[$kolonid] . "'>==>" . $row2[$kolonvalue] . "</option>";
          

             }mysqli_free_result($result2); 

             }mysqli_free_result($result1); 
            }
        }
        mysqli_free_result($result);
        echo "</select>";
    }

          /**
     * Tablo cizilmesini saglar Id degerleri ekrana yazilmaz. kopyala, duzenle ve sil butonlari istege bagli eklenir.
     * @param type $tablo = verinin getirilecegi tabloyu secer
     * @param type $array = Listelenmeyecek kolonlarin adlari virgulle ayrilarak yazilmalidir.
     * @param type $where = Sql uzerinde sorgulama yapilmasi icindir bos birakilirsa islem dikkate alinmaz.
     */
    
    public function drawtable($tablo, $array, $where, $edit){
        global $conn;
        $query_db = "SELECT * FROM $tablo $where";
        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        $m = "";  
        
        
    }
    public function timeago($datetime){
           $timestamp = strtotime($datetime);
           $currentTime = time();
           $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");
          
	   if( !empty($timestamp)) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}$diff = round($diff);
			return  $diff . " " . $strTime[$i] . "(s) ago ";
	   }else {
               return NULL ;
           }
    }
    
}