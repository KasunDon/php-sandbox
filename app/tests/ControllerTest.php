<?php

/**
 * Test Class for application controllers
 */
class ControllerTest extends TestCase {
    
    public static $TEST_CODE = "54ad21adf037d0b2047b23c6";

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
    
       /**
     * Testing Rendering embed code
     */
    public function testRenderingEmbedJs() {
        $this->call('GET', '/embed.js?c=' . self::$TEST_CODE);
        $this->assertResponseOk();
    }
    
    /**
     * Tesing Report-Issue POST
     */
    public function testReportIssue() {
        $this->call('POST', "/report-issue", array('email' => 'development@phpbox.info',
            'subject' => 'Tesing Controller', 'issue' => 'Tesing Controller ' . time(), 'vType' => 't1'));
        
        $this->assertResponseOk();
    }
    
    /**
     * Test Share Code
     */
    public function testShareCode(){
        $this->call('GET', "/share/" . self::$TEST_CODE);
        $this->assertResponseOk();
    }
    
    /**
     * Test Embed Code
     */
    public function testGetEmbedCode(){
        $this->call('GET', "/get-embed/" . self::$TEST_CODE);
        $this->assertResponseOk();
    }
    
    /**
     * Test Get Raw Code
     */
    public function testGetRawCode(){
        $this->call('GET', "/code/". self::$TEST_CODE . "/raw");
        $this->assertResponseOk();
    }
    
    /**
     * Test Send Feedback
     */
    public function testSendFeedback() {
        $this->call('POST', "/send-feedback", array('Feeback' => 'Tesing Feedback', 'vType' => 'f1'));
        $this->assertResponseOk();
    }
    
    /**
     *  Test Save Code
     */
    public function testSaveCode() {
        $this->call('POST', "/save-code", array('code' => "echo 'hello world';", 'output' => "hello world",
            'version' => '4.4.9',  'create_time' => \App\Models\Utils::datetime(), 'vType' => 'c1'));

        $this->assertResponseOk();
    }
}
