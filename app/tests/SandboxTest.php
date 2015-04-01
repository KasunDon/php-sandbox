<?php
use App\Models\PHPAPI;

/**
 * Test Class for Sandbox Runtime
 */
class SandboxTest extends TestCase {

    /**
     * Testing runtime environment model
     *
     * @return void
     */
//    public function testRuntimeModel() {
//        foreach (PHPAPI::versions() as $version) {
//            $eval = "testing works for " . $version;
//            $code = "echo '$eval';";
//
//            $sandbox = new PHPAPI($version, $code);
//            $output = $sandbox->execute();
//
//            $this->assertEquals($eval, $output);
//        }
//    }

    /**
     * Testing runtime environment access
     *
     * @return void
     */
    public function testRuntimeHttpAccess() {
        foreach (PHPAPI::versions() as $version) {
            $eval = "testing works for " . $version;
            $code = "echo '$eval';";

            $response = $this->call('POST', "/api/php/$version/run", array('v' => $version, 'code' => $code));

            $this->assertResponseOk();
            
            $output = json_decode($response->getContent());
            $this->assertEquals($eval, $output->output);
        }
    }

}
