<div class="container">
    <p>We are looking for ways to improve. Your feedback is very important as it help to deliver best user experience to everyone. We're really greatful to have your feedback. Thank you for your time. </p>
    <div class="row">
        <div class="col-md-12">
            <div id="feedback-success" class="alert alert-success" style="display: none;"><strong><span class="glyphicon glyphicon-send"></span> Feedback received!.</strong></div>

            <div id="feedback-error" class="alert alert-danger" style="display: none;"><span class="glyphicon glyphicon-alert"></span><strong> Ooops! Something went wrong. Please try again later.</strong></div>
        </div>
        <form id="feedback-form" role="form">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="InputMessage">Tell us your feedback</label>
                    <div class="input-group">
                        <textarea name="feedback" id="feedback-message" class="form-control" rows="12" minLength="3" required></textarea>
                        <span class="input-group-addon"></i></span></div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        SANDBOX.validation.feedback();
    });
</script>