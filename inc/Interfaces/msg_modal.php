<?php
include_once($dirs['Interfaces'] . 'header.php');
?>

<script>
window.onload = function() {
  $('#MSG').modal('show')
};
</script>

<!-- Modal -->
<div class="modal fade" id="MSG" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times" aria-hidden="true"></i>
        </button>
      </div>
      <div class="modal-body">
        <p><?= $MSG ?></p>
      </div>
    </div>
  </div>
</div>
