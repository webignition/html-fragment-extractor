<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetHtmlContentTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setHtmlContent('foo'));
    }
    
    
    public function testSetNonStringThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException', 'HTML content must be a string', 3);
        
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setHtmlContent(0));        
    }    
};