<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetColumnNumberTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setColumnNumber(1));
    }
    
    public function testSetAsNonIntegerThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException', 'Column number is not a number', 1);
        
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setColumnNumber("foo"));        
    }
    
    public function testSetBelowRangeThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException', 'Column number must be 1 or greater', 2);
        
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setColumnNumber(0));        
    }
    
};