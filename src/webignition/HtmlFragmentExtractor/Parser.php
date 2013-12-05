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
        
//        var_dump($this->getCharacterIndex() - $startPosition + ($startPosition - $this->getNearestNewlineToStartPosition()), substr($inputString, $startPosition, $fragmentLength));
//        exit();
        
        //$offset = $this->getCharacterIndex() - $startPosition + ($startPosition - $this->getNearestNewlineToStartPosition());
        $offset = $this->getCharacterIndex() - $startPosition;
        $fragment = substr($inputString, $startPosition, $fragmentLength);
        
        var_dump(substr($inputString, $this->getCharacterIndex(), 14));
//        var_dump($fragment);
////        var_dump(substr($fragment, 0, $offset));
////
//////        var_dump($startPosition);
//////        var_dump($this->getCharacterIndex());
//        exit();
        
        return array(
            'offset' => $offset,
            'fragment' => $fragment
        );

        //$focusCharacterIndex = $this->getCharacterIndexFromLineNumberAndColumnNumber();
        var_dump($startPosition);
        var_dump($endPosition);
        exit();
        
        var_dump(substr($this->inputString, $focusCharacterIndex - 20, 40));
        
        //$closestStartPosition = $this->getClosestStartPositionToColumnNumber();
        
        //var_dump($this->elementStartPositions, $this->elementEndPositions);
        exit();            
        
        $closestEndPosition = $this->getClosestEndPositionToColumnNumber($columnNumber);
        
        $endPosition = mb_strlen($inputString) - $closestStartPosition - (mb_strlen($inputString) - $closestEndPosition) + 1;
        
        return array(
            'offset' => $closestStartPosition,
            'fragment' => substr($inputString, $closestStartPosition, $endPosition)
        );
        
        var_dump($this->elementStartPositions);
        var_dump($this->elementEndPositions, $columnNumber, $inputString, $closestStartPosition, $closestEndPosition);
        
        var_dump("another case applies");
        
        exit();
    }
    
    
    private function getNearestNewlineToStartPosition() {
        $startPosition = $this->getFragmentStartPosition();
        
        for ($characterIndex = $startPosition; $characterIndex >= 0; $characterIndex--) {
            if ($this->inputString[$characterIndex] == "\n") {
                return $characterIndex;
            }
        }
        
        return 0;
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
    
    
    
    private function getClosestStartPositionToColumnNumber() {
        $focusCharacterIndex = $this->getCharacterIndexFromLineNumberAndColumnNumber();
        $closest = null;
        
        //foreach ($this->elementStartPositions as)
        
    }
    
    private function getClosestEndPositionToColumnNumber($columnNumber) {
        foreach ($this->elementEndPositions as $endPosition) {
            if ($endPosition >= $columnNumber) {
                return $endPosition;
            }
        }
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
        
        
        
//        var_dump($this->getCurrentState());
//        exit();
//        var_dump($this->getCurrentCharacter());
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