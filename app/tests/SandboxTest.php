<?php

/**
 * Test Class for Sandbox Runtime
 */
class SandboxTest extends TestCase {

    /**
     * Testing runtime environment model
     *
     * @return void
     */
    public function testRuntimeModel() {
        foreach (PHPSandBox::$VERSIONS as $version => $path) {
            $eval = "testing works for " . $version;
            $code = "echo '$eval';";

            $sandbox = new PHPSandBox($version, $code);
            $output = $sandbox->execute();

            $this->assertEquals($eval, $output);
        }
    }

    /**
     * Testing runtime environment access
     *
     * @return void
     */
    public function testRuntimeHttpAccess() {
        foreach (PHPSandBox::$VERSIONS as $version => $path) {
            $eval = "testing works for " . $version;
            $code = "echo '$eval';";

            $response = $this->call('POST', "/api/php/$version/run", array('v' => $version, 'code' => $code));

            $this->assertResponseOk();
            
            $output = json_decode($response->getContent());
            $this->assertEquals($eval, $output->output);
        }
    }

}
