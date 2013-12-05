<?php

namespace webignition\Tests\HtmlFragmentExtractor\GetFragment;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\HtmlFragmentExtractor\Configuration;

use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class AgainstSampleDocumentTest extends BaseTest {
    
    public function testGetElementFromLineContainingSingleElementTag() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(8);
        $configuration->setColumnNumber(20);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);        
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<link rel="openid.server" href="https://pip.verisignlabs.com/server">',
            'isLeftTruncated' => false,
            'isRightTruncated' => false
        ), $extractor->getFragment());
    }
    
    
    public function testGetElementFromFirstLineOfMultilineElement() {
        $configuration = new Configuration();
        $configuration->setLineNumber(5);
        $configuration->setColumnNumber(18);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);          
        
        $this->assertEquals(array(
            'offset' => 9,
            'fragment' => '<meta http-equiv="Content-Type"
              content="text/html; charset=UTF-8">',
            'isLeftTruncated' => false,
            'isRightTruncated' => false            
        ), $extractor->getFragment());
    }    
    
    public function testGetElementFromSecondLineOfMultilineElement() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(6);
        $configuration->setColumnNumber(31);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 62,
            'fragment' => '<meta http-equiv="Content-Type"
              content="text/html; charset=UTF-8">',
            'isLeftTruncated' => false,
            'isRightTruncated' => false            
        ), $extractor->getFragment());
    }     
    
    public function testGetElementFromLineContainingSingleStartTagAndSingleEndTag() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(4);
        $configuration->setColumnNumber(20);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<title>Jon Cram</title>',
            'isLeftTruncated' => false,
            'isRightTruncated' => false            
        ), $extractor->getFragment());
    }
    
    
    public function testGetElementfromLineContainingNestedElements() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(59);
        $configuration->setColumnNumber(27);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 18,
            'fragment' => '<i class="icon icon-envelope">',
            'isLeftTruncated' => false,
            'isRightTruncated' => false            
        ), $extractor->getFragment());        
    }
    
    
    public function testGetTrimmedLinefromLineContainingNestedElementsWithLtrimOffset() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(137);
        $configuration->setColumnNumber(21);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 8,
            'fragment' => '<span><div id="1"></div></span>',
            'isLeftTruncated' => false,
            'isRightTruncated' => false            
        ), $extractor->getFragment());        
    }    
    
    
    public function testGetTrimmedLinefromLineContainingNestedElementsWithoutLtrimOffset() {        
        $configuration = new Configuration();
        $configuration->setLineNumber(138);
        $configuration->setColumnNumber(12);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 11,
            'fragment' => '<span><div id="2"></div></span>',
            'isLeftTruncated' => false,
            'isRightTruncated' => false            
        ), $extractor->getFragment());        
    }     
    
    public function testGetTrimmedTruncatedElementTruncatedFromTheRight() {
        $configuration = new Configuration();
        $configuration->setLineNumber(139);
        $configuration->setColumnNumber(39);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 8,
            'fragment' => '<p>Lorem ipsum and so on and etc and this goes on for quite a while until it overruns the maximum full-line fragment length whic',
            'isLeftTruncated' => false,
            'isRightTruncated' => true            
        ), $extractor->getFragment());        
    }    
    
    public function testGetTrimmedTruncatedElementTruncatedFromTheLeft() {
        $configuration = new Configuration();
        $configuration->setLineNumber(139);
        $configuration->setColumnNumber(153);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);         
        
        $this->assertEquals(array(
            'offset' => 122,
            'fragment' => 'on and etc and this goes on for quite a while until it overruns the maximum full-line fragment length which has now happened</p>',
            'isLeftTruncated' => true,
            'isRightTruncated' => false            
        ), $extractor->getFragment());        
    }       
    
};