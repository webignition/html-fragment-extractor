<?php

namespace webignition\Tests\HtmlFragmentExtractor\GetFragment;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\HtmlFragmentExtractor\Configuration;

use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class AgainstSampleDocumentTest extends BaseTest {
    
    public function testGetFromLineContainingSingleElementTag() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(8);
        $configuration->setColumnNumber(20);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);        
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<link rel="openid.server" href="https://pip.verisignlabs.com/server">'
        ), $extractor->getFragment());
    }
    
    
    public function testGetFromFirstLineOfMultilineElement() {
        $configuration = new Configuration();
        $configuration->setLineNumber(5);
        $configuration->setColumnNumber(18);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);          
        
        $this->assertEquals(array(
            'offset' => 9,
            'fragment' => '<meta http-equiv="Content-Type"
              content="text/html; charset=UTF-8">'
        ), $extractor->getFragment());
    }    
    
    public function testGetFromSecondLineOfMultilineElement() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(6);
        $configuration->setColumnNumber(31);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 62,
            'fragment' => '<meta http-equiv="Content-Type"
              content="text/html; charset=UTF-8">'
        ), $extractor->getFragment());
    }     
    
    public function testGetFromLineContainingSingleStartTagAndSingleEndTag() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(4);
        $configuration->setColumnNumber(20);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<title>Jon Cram</title>'
        ), $extractor->getFragment());
    }
    
    
    public function testGetfromLineContainingNestedElements() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(59);
        $configuration->setColumnNumber(27);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 18,
            'fragment' => '<i class="icon icon-envelope">'
        ), $extractor->getFragment());        
    }
    
   
    
};