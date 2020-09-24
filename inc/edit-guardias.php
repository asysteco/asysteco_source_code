<div class="container" style="margin-top:75px;">
    <div class="row">
        <div class="col-xs-12">
            <div id="guardias-response"></div>
        </div>
        <div class="col-xs-12">
            <div id="loading" style='text-align: center; position: absolute; width: 100%; height: 100%;'>
                <img style="text-align: center; background-color: transparent;" src="resources/img/loading.gif" alt="Cargando...">
                <h2 id="loading-msg"></h2>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias'),
    $('#loading').hide()
})
</script>