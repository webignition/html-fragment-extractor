<?php

namespace webignition\Tests\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;

class SetScopeTest extends BaseTest { 
    
    public function testSetReturnsSelf() {
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setScope(HtmlFragmentExtractor::SCOPE_AUTO));
    }
    
    
    public function testSetInvalidValueThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException', 'Scope "foo" is not valid', 6);
        
        $extractor = new HtmlFragmentExtractor();
        $this->assertEquals($extractor, $extractor->setScope('foo'));        
    }    
};