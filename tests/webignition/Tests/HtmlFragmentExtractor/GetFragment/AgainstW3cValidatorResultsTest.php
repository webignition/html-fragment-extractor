<?php

namespace webignition\Tests\HtmlFragmentExtractor\GetFragment;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class AgainstW3cValidatorResultsTest extends BaseTest {    
    
    public function testAgainstW3cHtmlValidatorOutput01() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(59);
        $extractor->setColumnNumber(14);
        $extractor->setHtmlContent($this->getFixture('sample02.html'));
        
        $this->assertEquals(array(
            'offset' => 12,
            'fragment' => '<body  lang="es" id="cms">'
        ), $extractor->getFragment());           
    }
    
    
    public function testAgainstW3cHtmlValidatorOutput02() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(115);
        $extractor->setColumnNumber(121);
        $extractor->setHtmlContent($this->getFixture('sample02.html'));
        
        $this->assertEquals(array(
            'offset' => 117,
            'fragment' => '<div style="float:left; width:38px; height:27px;border:1px solid #000000;  background-color:#FFFF00;margin-right:2px"></div>'
        ), $extractor->getFragment());           
    }      
};