<?php 
for ($i = 1; $i <= 5; $i++) {     echo "Angka: $i <br>"; 
} 
?> 

<?php 
$i = 1; while ($i <= 5) {     echo "Angka: $i <br>"; 
    $i++; 
} 
?> 

<?php 
$buah = array("apel", "jeruk", 
"mangga"); foreach ($buah as $item) {     echo $item . "<br>"; 
} 
?>