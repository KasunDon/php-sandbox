<div class="container">
    <p>If you're experiencing any issues, Please feel free to report back to us. simply fill in following form with description of the problem. We'll try to resolve the problem soon as we can. Thank you for your corporation.</p>
    <div class="row">
        <div class="col-md-12" >
            <div id="report-success" class="alert alert-success" style="display: none;"><strong id="success-message"><span class="glyphicon glyphicon-send"></span> Issue has been reported successfully!.</strong></div>	  
            <div id="report-error" class="alert alert-danger" style="display: none;"><span class="glyphicon glyphicon-alert"></span><strong id="error-message">Ooops! Something went wrong. Please try again later.</strong></div>
        </div>
        <form id="report-form" role="form">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="report-email" name="email" placeholder="Enter Email" required  >
                        <span class="input-group-addon"></span></div>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="subject" id="report-subject" minLength="4" placeholder="Enter Subject" required>
                        <span class="input-group-addon"></i></span></div>
                </div>

                <div class="form-group">
                    <label for="message">Describe your issue</label>
                    <div class="input-group">
                        <textarea name="message" id="report-issue" class="form-control" rows="5" minLength="20" required></textarea>
                        <span class="input-group-addon"></i></span></div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        SANDBOX.validation.issue();
    });
</script>