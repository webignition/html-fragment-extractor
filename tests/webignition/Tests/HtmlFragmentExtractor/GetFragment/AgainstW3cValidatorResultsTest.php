<?php

namespace webignition\Tests\HtmlFragmentExtractor\GetFragment;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\HtmlFragmentExtractor\Configuration;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class AgainstW3cValidatorResultsTest extends BaseTest {    
    
    public function testAgainstW3cHtmlValidatorOutput01() {
        $configuration = new Configuration();
        $configuration->setLineNumber(59);
        $configuration->setColumnNumber(14);
        $configuration->setHtmlContent($this->getFixture('sample02.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration); 
        
        $this->assertEquals(array(
            'offset' => 12,
            'fragment' => '<body  lang="es" id="cms">'
        ), $extractor->getFragment());           
    }
    
    
    public function testAgainstW3cHtmlValidatorOutput02() {
        $configuration = new Configuration();
        $configuration->setLineNumber(115);
        $configuration->setColumnNumber(121);
        $configuration->setHtmlContent($this->getFixture('sample02.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);
        
        $this->assertEquals(array(
            'offset' => 117,
            'fragment' => '<div style="float:left; width:38px; height:27px;border:1px solid #000000;  background-color:#FFFF00;margin-right:2px"></div>'
        ), $extractor->getFragment());           
    }      
};