<div class="container" style="margin-top:75px;">
    <div class="row">
        <div class="col-xs-12">
            <div id="guardias-response"></div>
        </div>
    </div>
</div>
<div class="act-response" hidden></div>
<script>
$(document).ready(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias')
})
</script>