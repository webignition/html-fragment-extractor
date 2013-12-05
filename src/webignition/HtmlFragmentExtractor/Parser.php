<?php

namespace webignition\HtmlFragmentExtractor;

use webignition\StringParser\StringParser;

class Parser extends StringParser {
    
    /**
     * states:
     * 
     * done (?)
     * in quoted attribute
     * seeking
     * 
     */   
    
    const STATE_SEEKING = 1;
    const STATE_IN_QUOTED_ATTRIBUTE = 2;   
    
    const TAG_START_CHARACTER = '<';
    const TAG_END_CHARACTER = '>';
    const ATTRIBUTE_WRAPPER_DQUOT = '"';
    const ATTRIBUTE_WRAPPER_QUOT = "'";

    private $elementStartPositions = array();
    private $elementEndPositions = array();
    
    private $lineNumber = null;
    private $columnNumber = null;
    
    private $inputString = null;
    private $characterIndex = null;    
    
    public function parse($inputString, $lineNumber = null, $columnNumber = null) {
        $this->lineNumber = $lineNumber;
        $this->columnNumber = $columnNumber;
        $this->inputString = $inputString;
        
        parent::parse($inputString);
        
        if ($this->hasNoStartPositionsOrNoEndPositions()) {
            return array(
                'offset' => 0,
                'fragment' => $inputString
            );
        }
        
        $startPosition = $this->getFragmentStartPosition();
        $fragmentLength = $this->getFragmentLength();

        $offset = $this->getCharacterIndex() - $startPosition;
        $fragment = substr($inputString, $startPosition, $fragmentLength);
        
        return array(
            'offset' => $offset,
            'fragment' => $fragment
        );
    }

    

    /**
     * 
     * @return int
     */
    private function getFragmentLength() {
        return $this->getInputStringLength() - $this->getFragmentStartPosition() - ($this->getInputStringLength() - $this->elementEndPositions[0]) + 1;
    }
    
    
    /**
     * 
     * @return int
     */
    private function getFragmentStartPosition() {
        return $this->elementStartPositions[count($this->elementStartPositions) - 1];
    }
    
    
    
    /**
     * 
     * @return int
     */
    private function getCharacterIndex() {
        if (is_null($this->characterIndex)) {
            $this->characterIndex = $this->getCharacterIndexFromLineNumberAndColumnNumber();
        }
        
        return $this->characterIndex;
    }
    
    
    private function getCharacterIndexFromLineNumberAndColumnNumber() {
        $inputStringLength = $this->getInputStringLength();
        
        $currentLineNumber = 0;
        $lineIndexes = array();
        
        for ($characterIndex = 0; $characterIndex < $inputStringLength; $characterIndex++) {
            if ($this->inputString[$characterIndex] == "\n") {
                $currentLineNumber++;
                $lineIndexes[] = $characterIndex;
            }
            
            if ($currentLineNumber == $this->lineNumber - 1) {
                return $lineIndexes[$currentLineNumber - 1] + $this->columnNumber;
            }
        }
        
        return null;
    }   
    
    
    /**
     * 
     * @return boolean
     */
    private function hasNoStartPositionsOrNoEndPositions() {
        return count($this->elementStartPositions) === 0 || count($this->elementEndPositions) === 0;
    }    
    
    protected function parseCurrentCharacter() {        
        switch ($this->getCurrentState()) {
            case self::STATE_UNKNOWN:             
                $this->setCurrentState(self::STATE_SEEKING);                
                $this->incrementCurrentCharacterPointer();
                break;
            
            case self::STATE_SEEKING:
                if ($this->isCurrentCharacterTagStartCharacter() && $this->getCurrentCharacterPointer() < $this->getCharacterIndex()) {
                    $this->elementStartPositions[] = $this->getCurrentCharacterPointer();
                }
                
                if ($this->isCurrentCharacterTagEndCharacter() && $this->getCurrentCharacterPointer() > $this->getCharacterIndex()) {
                    $this->elementEndPositions[] = $this->getCurrentCharacterPointer();
                }                

                $this->incrementCurrentCharacterPointer();
                break;
        }
    } 
    
    
    /**
     * 
     * @return boolean
     */
    private function isCurrentCharacterTagStartCharacter() {
        return $this->getCurrentCharacter() == self::TAG_START_CHARACTER;
    }    
    
    
    /**
     * 
     * @return boolean
     */
    private function isCurrentCharacterTagEndCharacter() {
        return $this->getCurrentCharacter() == self::TAG_END_CHARACTER;
    }
}