<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetColumnNumberTest extends BaseTest { 
    
    public function testSetColumnNumberReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setColumnNumber(1));
    }
    
};