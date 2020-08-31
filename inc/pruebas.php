<div class="container" style="margin-top:75px;">
    <div class="row">
        <div class="col-xs-12">
            <div id="guardias-response"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    var profesor_act = $('#profesor_act').attr('profesor');
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias')
})
</script>
