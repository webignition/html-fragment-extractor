<?php

namespace webignition\Tests\HtmlFragmentExtractor\Configuration;

use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class SetColumnNumberTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setColumnNumber(1));
    }
    
    public function testSetAsNonIntegerThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'Column number is not a number', 3);
        
        $configuration = new Configuration();
        $configuration->setColumnNumber("foo");        
    }
    
    public function testSetBelowRangeThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'Column number must be 1 or greater', 4);
        
        $configuration = new Configuration();
        $configuration->setColumnNumber(0);        
    }
    
};