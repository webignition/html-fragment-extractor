<?php

namespace webignition\HtmlFragmentExtractor;

use webignition\StringParser\StringParser;

class Parser extends StringParser {
    
    const STATE_SEEKING = 1;
    const STATE_IN_QUOTED_ATTRIBUTE = 2;   
    
    const TAG_START_CHARACTER = '<';
    const TAG_END_CHARACTER = '>';
    const ATTRIBUTE_WRAPPER_DQUOT = '"';
    const ATTRIBUTE_WRAPPER_QUOT = "'";
    
    const DEFAULT_FRAGMENT_MINIMUM_LENGTH = 16;   
    const DEFAULT_FRAGMENT_MAXIMUM_LENGTH = 128;

    private $elementStartPositions = array();
    private $elementEndPositions = array();
    
    private $lineNumber = null;
    private $columnNumber = null;
    
    private $inputString = null;
    private $characterIndex = null;
    
    private $offset = null;
    private $fragment = null;
    private $isLeftTruncated = false;
    private $isRightTruncated = false;
    
    private $fragmentStartPosition = null;
    private $fragmentEndPosition = null;
    
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
        
        $fragment = $this->getFragment();
        
        return array(
            'offset' => $this->getOffset(),
            'fragment' => $fragment,
            'isLeftTruncated' => $this->isLeftTruncated,
            'isRightTruncated' => $this->isRightTruncated
        );
    }
    
    
    /**
     * 
     * @return int
     */
    private function getOffset() {
        if (is_null($this->offset)) {
            $this->offset = $this->getCharacterIndex() - $this->getFragmentStartPosition();
        }
        
        return $this->offset;
    }
    
    
    /**
     * 
     * @return string
     */
    private function getFragment() {
        $this->fragment = substr($this->inputString, $this->getFragmentStartPosition(), $this->getFragmentLength());
        
        if ($this->fragmentRequiresExpanding() && !$this->isMultilineFragment()) {
            $fragmentLineIndices = $this->getFragmentLineStartAndEndPositions();                
            $this->fragmentStartPosition = $fragmentLineIndices['start'] + $this->getFragmentLineLtrimOffset();
            $this->fragmentEndPosition = $fragmentLineIndices['end'] - $this->getFragmentLineRtrimOffset() - 1;

            return $this->getFragment();
        }        
        
        if ($this->fragmentRequiresTruncating() && !$this->isMultilineFragment()) {
            if ($this->getOffset() <(int)ceil(strlen($this->fragment)/2)) { 
                $this->fragmentEndPosition--;
                $this->isRightTruncated = true;
            } else {                
                $this->fragmentStartPosition++;
                $this->isLeftTruncated = true;
            }

            return $this->getFragment();
        }        
        
        return $this->fragment;
    }
    
    
    /**
     * 
     * @return int
     */
    private function getFragmentLineLtrimOffset() {
        $fragmentLine = $this->getFragmentLine();
        $fragmentLineLength = strlen($fragmentLine);
        $offset = 0;
        
        for ($characterIndex = 0; $characterIndex < $fragmentLineLength; $characterIndex++) {
            if (trim($fragmentLine[$characterIndex]) == '') {
                $offset++;
            } else {
                return $offset;
            }
        }
        
        return $offset;
    }
    
    
    /**
     * 
     * @return int
     */
    private function getFragmentLineRtrimOffset() {
        $fragmentLine = $this->getFragmentLine();
        $fragmentLineLength = strlen($fragmentLine);
        $offset = 0;
    
        for ($characterIndex = $fragmentLineLength - 1; $characterIndex >= 0; $characterIndex--) {
            if (trim($fragmentLine[$characterIndex]) == '') {
                $offset++;
            } else {
                return $offset;
            }
        }
        
        return $offset;
    }    
    

    
    /**
     * 
     * @return string
     */
    private function getFragmentLine() {        
        $fragmentLineIndices = $this->getFragmentLineStartAndEndPositions();
        
        return substr($this->inputString, $fragmentLineIndices['start'], $fragmentLineIndices['end'] - $fragmentLineIndices['start']);
    }
    
    
    /**
     * Get start and end indicies for line containing fragment
     * 
     * @return array
     */
    private function getFragmentLineStartAndEndPositions() {
        return array(
            'start' => strrpos(substr($this->inputString, 0, $this->getFragmentStartPosition()), "\n") + 1,
            'end' => strpos($this->inputString, "\n", $this->getFragmentStartPosition())
        );
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function fragmentRequiresExpanding() {        
        return strlen($this->fragment) <= self::DEFAULT_FRAGMENT_MINIMUM_LENGTH;
    }
    

    /**
     * 
     * @return boolean
     */    
    private function fragmentRequiresTruncating() {        
        return strlen($this->fragment) > self::DEFAULT_FRAGMENT_MAXIMUM_LENGTH;
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    private function isMultilineFragment() {
        return substr_count($this->fragment, "\n") > 0;
    }
    
    

    /**
     * 
     * @return int
     */
    private function getFragmentLength() {
        return $this->getInputStringLength() - $this->getFragmentStartPosition() - ($this->getInputStringLength() - $this->getFragmentEndPosition()) + 1;
    }
    
    
    /**
     * 
     * @return int
     */
    private function getFragmentStartPosition() {
        if (is_null($this->fragmentStartPosition)) {
            $this->fragmentStartPosition = $this->deriveFragmentStartPosition();
        }
        
        return $this->fragmentStartPosition;
    }   
    
    /**
     * 
     * @return int
     */
    private function deriveFragmentStartPosition() {
        return $this->elementStartPositions[count($this->elementStartPositions) - 1];
    }
    
    
    /**
     * 
     * @return int
     */
    private function getFragmentEndPosition() {
        if (is_null($this->fragmentEndPosition)) {
            $this->fragmentEndPosition = $this->deriveFragmentEndPosition();
        }
        
        return $this->fragmentEndPosition;
    }
    
    
    /**
     * 
     * @return int
     */
    private function deriveFragmentEndPosition() {
        return $this->elementEndPositions[0];
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