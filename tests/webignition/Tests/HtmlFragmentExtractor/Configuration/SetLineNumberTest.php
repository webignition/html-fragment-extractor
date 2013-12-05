<?php

namespace webignition\Tests\HtmlFragmentExtractor\Configuration;

use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class SetLineNumberTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setLineNumber(1));
    }
    
    public function testSetAsNonIntegerThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'Line number is not a number', 1);
        
        $configuration = new Configuration();
        $configuration->setLineNumber("foo");        
    }
    
    public function testSetBelowRangeThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'Line number must be 1 or greater', 2);
        
        $configuration = new Configuration();
        $configuration->setLineNumber(0);        
    }    
};