<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class SetConfigurationTest extends BaseTest { 

    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $extractor = new HtmlFragmentExtractor();
        
        $this->assertEquals($extractor, $extractor->setConfiguration($configuration));
    }
    
};