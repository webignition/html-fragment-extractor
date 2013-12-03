<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetLineNumberTest extends BaseTest { 
    
    public function testSetLineNumberReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setLineNumber(1));
    }
    
};