<h2 style="text-align:center; color:red;">
Print Variables $_SERVER</h2>
<table border cellpadding=2>
  <tr> <th> Variable </th> <th> Value </th> </tr>
  <?php
    foreach($_SERVER as $index => $value) {
      echo "<tr><td>$index</td> <td>$value</td>\n";
    }
  ?>
</table>
