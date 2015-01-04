<div class="container">
    <p>Share the output across social network's</p>
    <div class="row">
        <div class="col-md-12">
            <div id="social-error" class="alert alert-danger" style="display: none;"><span class="glyphicon glyphicon-alert"></span><strong> Ooops! Something went wrong. Please try again later.</strong></div>
            <div id="progress" class="progress" style="display: none;">
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    Saving ...
                </div>
            </div>
            <div id="modal-view-link-zone" class="panel panel-default" style="display: none;">
                <div class="panel-body">
                    <a id="modal-view-link"></a>
                </div>
            </div>


            <div id="social-links" class="panel panel-default" style="display: none;">
                <!-- Default panel contents -->
                <div class="panel-heading">Social Networks</div>
                <div class="panel-body">
                    <div id="social-zone" style="padding-left: 20px">

                    </div>
                </div>
            </div>



        </div>
    </div>
</div>

<script>
    $(document).ready(function() {


        var editor = ace.edit("editor");
        if (SANDBOX.core.viewId == null) {
            $('#progress').show();
            setTimeout(function() {
                $.post('/save-code', {code: editor.getValue(), output:
                            SANDBOX.core.output, create_time: SANDBOX.core.create_time,
                    version: SANDBOX.core.version, vType: 'c1'}, function(data) {
                    data = JSON.parse(data);
                    SANDBOX.core.viewId = data.viewId;
                    SANDBOX.core.viewLink = data.viewLink + data.viewId;
                    $('#view-link').text(SANDBOX.core.viewLink);
                    $('#view-link').attr('href', SANDBOX.core.viewLink);
                    $('#view-link-zone').show();
                    $('#progress').hide();
                    $('#save_share').text('Share');
                    $('#modal-view-link-zone').show();
                    $('#modal-view-link').text(SANDBOX.core.viewLink);
                    $('#modal-view-link').attr('href', SANDBOX.core.viewLink);
                    $('#social-zone').share({
                        networks: ['facebook', 'pinterest', 'googleplus', 'twitter', 'linkedin', 'tumblr', 'in1', 'email', 'stumbleupon', 'digg'],
                        urlToShare: SANDBOX.core.viewLink,
                    });
                    $('#social-links').show();
                }).fail(function() {
                    $('#progress').hide();
                    SANDBOX.utils.closeModal(4000);
                    $('#social-error').show();
                });
            }, 2000);
        } else {
            $('#modal-view-link-zone').show();
            $('#modal-view-link').text(SANDBOX.core.viewLink);
            $('#modal-view-link').attr('href', SANDBOX.core.viewLink);
            $('#social-zone').share({
                networks: ['facebook', 'pinterest', 'googleplus', 'twitter', 'linkedin', 'tumblr', 'in1', 'email', 'stumbleupon', 'digg'],
                urlToShare: SANDBOX.core.viewLink,
            });
            $('#social-links').show();
        }
    });
</script>

