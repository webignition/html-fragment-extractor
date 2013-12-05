<?php

namespace webignition\Tests\HtmlFragmentExtractor\GetFragment;

use webignition\HtmlFragmentExtractor\HtmlFragmentExtractor;
use webignition\HtmlFragmentExtractor\Configuration;

use webignition\Tests\HtmlFragmentExtractor\BaseTest;

class OutOfBoundsTest extends BaseTest { 

    public function testOutOfBoundsLineNumberThrowsOutOfBoundsException() {
        $this->setExpectedException('OutOfBoundsException', 'Given line (500) not present in HTML content', 1);
        
        $configuration = new Configuration();
        $configuration->setLineNumber(500);
        $configuration->setColumnNumber(20);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);
        
        $extractor->getFragment();
    }   
    
    
    public function testOutOfBoundsColumnNumberThrowsOutOfBoundsException() {
        $this->setExpectedException('OutOfBoundsException', 'Given column (200 not present in line 4)', 2);
        
        $configuration = new Configuration();
        $configuration->setLineNumber(4);
        $configuration->setColumnNumber(200);
        $configuration->setHtmlContent($this->getFixture('sample01.html'));  
        
        $extractor = new HtmlFragmentExtractor();
        $extractor->setConfiguration($configuration);
        
        $extractor->getFragment();
    }
   
    
};