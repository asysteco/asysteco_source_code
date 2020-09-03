<?php
echo '<div class="filtro_edificio">';
if($response = $class->query("SELECT DISTINCT Edificio FROM Horarios WHERE Edificio<>0"))
{
  if($response->num_rows > 0)
  {
    while($edificio = $response->fetch_assoc())
    {
      echo "<a href='index.php?ACTION=home&OPT=Edificio&Numero=$edificio'><button type='button' class='btn btn-success'><span></span> Edificio 1</button></a>";
    }
  }
}
echo "<a href='index.php'><button type='button' class='btn btn-success'><span></span> Todos</button></a>";
echo '</div>';