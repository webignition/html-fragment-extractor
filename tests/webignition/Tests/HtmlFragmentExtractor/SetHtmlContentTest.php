<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetHtmlContentTest extends BaseTest { 
    
    public function testSetHtmlContentReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setHtmlContent('foo'));
    }
    
};