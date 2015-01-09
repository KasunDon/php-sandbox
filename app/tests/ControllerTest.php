<?php

/**
 * Test Class for application controllers
 */
class ControllerTest extends TestCase {

    /**
     * Testing Root Controller
     */
    public function testRootViewControllers() {
        $this->call('GET', '/');
        $this->assertResponseOk();
    }

    /**
     * Testing Terms Controller
     */
    public function testTermsViewControllers() {
        $this->call('GET', '/view-terms');
        $this->assertResponseOk();
    }

    /**
     * Testing Social Controller
     */
    public function testSocialViewControllers() {
        $this->call('GET', '/view-social');
        $this->assertResponseOk();
    }

    /**
     * Testing Feedback Controller
     */
    public function testFeedbackViewControllers() {
        $this->call('GET', '/view-feedback');
        $this->assertResponseOk();
    }

    /**
     * Testing Service Controller
     */
    public function testServiceViewControllers() {
        $this->call('GET', '/view-service');
        $this->assertResponseOk();
    }

    /**
     * Testing Report-Issue Controller
     */
    public function testReportIssueViewControllers() {
        $this->call('GET', '/view-report-issue');
        $this->assertResponseOk();
    }

}
