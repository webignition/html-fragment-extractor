<?php

namespace webignition\Tests\HtmlFragmentExtractor\Configuration;

use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class SetHtmlContentTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setHtmlContent('foo'));
    }
    
    
    public function testSetNonStringThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'HTML content must be a string', 5);
        
        $configuration = new Configuration();
        $configuration->setHtmlContent(0);        
    }    
};