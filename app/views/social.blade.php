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
                    <b> Link : </b>   <a id="modal-view-link"></a>
                </div>
            </div>
            
            <div id="modal-embed"></div>

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
       SANDBOX.core.socialTab();
    });
</script>

