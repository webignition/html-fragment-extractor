<?php

namespace webignition\HtmlFragmentExtractor;

use webignition\HtmlFragmentExtractor\Configuration;

class HtmlFragmentExtractor {
    
    /**
     *
     * @var array
     */
    private $htmlContentLines = null;
    
    
    /**
     *
     * @var \webignition\HtmlFragmentExtractor\Configuration 
     */
    private $configuration = null;

    
    /**
     * 
     * @param \webignition\HtmlFragmentExtractor\Configuration $configuration
     * @return \webignition\HtmlFragmentExtractor\HtmlFragmentExtractor
     */
    public function setConfiguration(Configuration $configuration) {
        $this->configuration = $configuration;
        return $this;
    }
    
    
    /**
     * 
     * @return \webignition\HtmlFragmentExtractor\Configuration
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            throw new \RuntimeException('Configuration not set', 1);
        }
        
        return $this->configuration;
    }

    
    
    /**
     * 
     * @return array
     * @throws \OutOfBoundsException
     */
    public function getFragment() { 
        if (!$this->hasLine()) {
            throw new \OutOfBoundsException('Given line ('.$this->getConfiguration()->getLineNumber().') not present in HTML content', 1);
        }
        
        if (!$this->hasColumn()) {
            throw new \OutOfBoundsException('Given column ('.$this->getConfiguration()->getColumnNumber().' not present in line '.$this->getConfiguration()->getLineNumber().')', 2);
        }

        
        $parser = new Parser();
        return $parser->parse($this->getConfiguration()->getHtmlContent(), $this->getConfiguration()->getLineNumber(), $this->getConfiguration()->getColumnNumber());
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasLine() {
        return !is_null($this->getLine());
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasColumn() {
        if (is_null($this->getConfiguration()->getColumnNumber())) {
            return false;
        }  
        
        return $this->getConfiguration()->getColumnNumber() <= mb_strlen($this->getLine());
    }    
    
    
    /**
     * 
     * @return string
     */
    private function getLine() {
        $lines = $this->getLines();
        return isset($lines[$this->getLineIndex()]) ? $lines[$this->getLineIndex()] : null;
    }
    
    
    /**
     * 
     * @return int
     */
    private function getLineIndex() {
        if (is_null($this->getConfiguration()->getLineNumber())) {
            return null;
        }
        
        return $this->getConfiguration()->getLineNumber() - 1;
    }
    
    
    /**
     * 
     * @return array
     */
    private function getLines() {
        if (is_null($this->htmlContentLines)) {
            $this->htmlContentLines = explode("\n", $this->getConfiguration()->getHtmlContent());
        }
        
        return $this->htmlContentLines;
    }
    
} 