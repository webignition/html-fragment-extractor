<?php

namespace webignition\Tests\HtmlFragmentExtractor\Configuration;

use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class SetScopeTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setScope(Configuration::SCOPE_AUTO));
    }
    
    
    public function testSetInvalidValueThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'Scope "foo" is not valid', 6);
        
        $configuration = new Configuration();
        $configuration->setScope('foo');        
    }    
};