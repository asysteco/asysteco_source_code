<div class="container" style="margin-top:75px;">
    <div class="row">
        <div class="col-12">
            <div id="guardias-response"></div>
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