<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class GetConfigurationTest extends BaseTest { 

    public function testGetBeforeSetThrowsRuntimeException() {
        $this->setExpectedException('RuntimeException', 'Configuration not set', 1);
        
        $extractor = new HtmlFragmentExtractor();        
        $extractor->getConfiguration();
    }
    
};