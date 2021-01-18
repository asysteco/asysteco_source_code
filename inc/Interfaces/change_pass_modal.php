<?php

if (empty($alertTitle) && !empty($alertMessage)) {
  $alertColor = $alertType === 'Success' ? "green": "red";
  $alertTitle = $alertType === 'Success' ? '¡Correcto!': '¡Error!';

  if ($cambiada) {
?>
    <script>
      setTimeout(function () { location.href = 'index.php?ACTION=logout' }, 1000);
    </script>
<?php
  }
?>


<!-- Modal -->
<div class="modal fade" id="advice-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" style="color: <?= $alertColor ?>;"> 
          <?= $alertTitle ?>
        </h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p style="color: <?= $alertColor ?>;">
          <?= $alertMessage ?>
        </p>
      </div>
    </div>
  </div>
</div>
<script>
  window.onload = function() {
    $('#advice-modal').modal('show')
  };
</script>
<?php
}
?>
