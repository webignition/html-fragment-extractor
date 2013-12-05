<?php

namespace webignition\HtmlFragmentExtractor;

class Configuration {
    
    const SCOPE_AUTO = 'auto';
    const SCOPE_ELEMENT = 'element';
    const SCOPE_PARENT = 'parent';
    const SCOPE_GRANDPARENT = 'grandparent';
    
    /**
     * Collection of allowed scope values
     * 
     * @var array
     */
    private $allowedScopes = array(
        self::SCOPE_AUTO,
        self::SCOPE_ELEMENT,
        self::SCOPE_PARENT,
        self::SCOPE_GRANDPARENT        
    );
    
    
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
     * @var array
     */
    private $htmlContentLines = null;
    
    
    /**
     *
     * @var string
     */
    private $scope = self::SCOPE_AUTO;
    
    

    /**
     * 
     * @param int $lineNumber
     * @return \webignition\HtmlFragmentExtractor\HtmlFragmentExtractor
     * @throws \UnexpectedValueException
     */
    public function setLineNumber($lineNumber) {
        $lineNumber = filter_var($lineNumber, FILTER_VALIDATE_INT);
        if ($lineNumber === false) {
            throw new \UnexpectedValueException('Line number is not a number', 1);
        }
        
        if ($lineNumber < 1) {
            throw new \UnexpectedValueException('Line number must be 1 or greater', 2);
        }
        
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
     * @throws \UnexpectedValueException
     */
    public function setColumnNumber($columnNumber) {
        $columnNumber = filter_var($columnNumber, FILTER_VALIDATE_INT);
        if ($columnNumber === false) {
            throw new \UnexpectedValueException('Column number is not a number', 3);
        }
        
        if ($columnNumber < 1) {
            throw new \UnexpectedValueException('Column number must be 1 or greater', 4);
        }
        
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
     * @throws \UnexpectedValueException
     */
    public function setHtmlContent($htmlContent) {
        if (!is_string($htmlContent)) {
            throw new \UnexpectedValueException('HTML content must be a string', 5);
        }
        
        $this->htmlContent = $htmlContent;
        $this->htmlContentLines = null;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getHtmlContent() {
        return $this->htmlContent;
    }
    
    
    /**
     * 
     * @param string $scope
     * @return \webignition\HtmlFragmentExtractor\HtmlFragmentExtractor
     * @throws \UnexpectedValueException
     */
    public function setScope($scope) {
        if (!in_array($scope, $this->allowedScopes)) {
            throw new \UnexpectedValueException('Scope "'.$scope.'" is not valid', 6);
        }
        
        $this->scope = $scope;
        return $this;
    }
} 