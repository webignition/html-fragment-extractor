<?php

namespace webignition\HtmlFragmentExtractor;

class HtmlFragmentExtractor {
    
    /**
     *
     * @var int
     */
    private $lineNumber = null;
    
    
    /**
     *
     * @var int
     */
    private $columnNumber = null;
    
    
    /**
     *
     * @var string
     */
    private $htmlContent = null;
    
    
    /**
     * 
     * @param int $lineNumber
     * @return \webignition\HtmlFragmentExtractor\HtmlFragmentExtractor
     */
    public function setLineNumber($lineNumber) {
        $this->lineNumber = $lineNumber;
        return $this;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getLineNumber() {
        return $this->lineNumber;
    }
    
    
    /**
     * 
     * @param int $columnNumber
     * @return \webignition\HtmlFragmentExtractor\HtmlFragmentExtractor
     */
    public function setColumnNumber($columnNumber) {
        $this->columnNumber = $columnNumber;
        return $this;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getColumnNumber() {
        return $this->columnNumber;
    }
    
    
    /**
     * 
     * @param string $htmlContent
     * @return \webignition\HtmlFragmentExtractor\HtmlFragmentExtractor
     */
    public function setHtmlContent($htmlContent) {
        $this->htmlContent = $htmlContent;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getHtmlContent() {
        return $this->htmlContent;
    }
    
} 