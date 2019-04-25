<?php
unset($_FILES['excel']);
?>
<form method="POST" action="upload.php" enctype="multipart/form-data">
   Arquivo:<br>
   <input type="file" name="excel"/><br/>
   <input type="submit" name="Enviar"/><br/>
</form>
<a href="../principal.php"> Voltar</a>


