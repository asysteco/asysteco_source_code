<div class="container" style="margin-top:75px;">
    <div class="row">
        <div class="col-xs-12">
            <div id="guardias-response"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias')
})
</script>
<script>
$('#select-edit-guardias').change(function(){
    profesor = $(this).val(),
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+profesor)
})
</script>
