<?php

namespace webignition\Tests\HtmlFragmentExtractor\GetFragment;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class AgainstSampleDocumentTest extends BaseTest { 

    public function testOutOfBoundsLineNumberThrowsOutOfBoundsException() {
        $this->setExpectedException('OutOfBoundsException', 'Given line (500) not present in HTML content', 1);
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(500);
        $extractor->setColumnNumber(20);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $extractor->getFragment();
    }   
    
    
    public function testOutOfBoundsColumnNumberThrowsOutOfBoundsException() {
        $this->setExpectedException('OutOfBoundsException', 'Given column (200 not present in line 4)', 2);
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(4);
        $extractor->setColumnNumber(200);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $extractor->getFragment();
    }     
    
    
    public function testGetFromLineContainingSingleElementTag() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(8);
        $extractor->setColumnNumber(20);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<link rel="openid.server" href="https://pip.verisignlabs.com/server">'
        ), $extractor->getFragment());
    }
    
    
    public function testGetFromFirstLineOfMultilineElement() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(5);
        $extractor->setColumnNumber(18);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $this->assertEquals(array(
            'offset' => 9,
            'fragment' => '<meta http-equiv="Content-Type"
              content="text/html; charset=UTF-8">'
        ), $extractor->getFragment());
    }    
    
    public function testGetFromSecondLineOfMultilineElement() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(6);
        $extractor->setColumnNumber(31);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $this->assertEquals(array(
            'offset' => 62,
            'fragment' => '<meta http-equiv="Content-Type"
              content="text/html; charset=UTF-8">'
        ), $extractor->getFragment());
    }     
    
    public function testGetFromLineContainingSingleStartTagAndSingleEndTag() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(4);
        $extractor->setColumnNumber(20);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<title>Jon Cram</title>'
        ), $extractor->getFragment());
    }
    
    
    public function testGetfromLineContainingNestedElements() {
        $extractor = new HtmlFragmentExtractor();
        $extractor->setLineNumber(59);
        $extractor->setColumnNumber(27);
        $extractor->setHtmlContent($this->getFixture('sample01.html'));
        
        $this->assertEquals(array(
            'offset' => 18,
            'fragment' => '<i class="icon icon-envelope">'
        ), $extractor->getFragment());        
    }
    
   
    
};