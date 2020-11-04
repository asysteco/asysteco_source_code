<div class="container" style="margin-top:75px;">
    <div class="row">
        <div class="col-xs-12">
            <div id="guardias-response"></div>
        </div>
    </div>
</div>

<div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center; z-index: -1;">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    urlPath = 'index.php?ACTION=horarios&OPT=guardias';
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
                $('#loading-msg').html('Cargando guardias...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            $('#guardias-response').html(data);
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
        }
    });
})
</script>