<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div id="guardias-response"></div>
        </div>
    </div>
</div>
<script>
var profesor_act = $('#profesor_act').attr('profesor'); 
$(document).ready(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias')
})
</script>
