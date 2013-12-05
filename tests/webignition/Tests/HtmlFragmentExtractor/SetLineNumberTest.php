<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetLineNumberTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setLineNumber(1));
    }
    
    public function testSetAsNonIntegerThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException', 'Line number is not a number', 1);
        
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setLineNumber("foo"));        
    }
    
    public function testSetBelowRangeThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException', 'Line number must be 1 or greater', 2);
        
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setLineNumber(0));        
    }    
};