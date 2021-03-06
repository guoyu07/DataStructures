<?php
/**
 * DataStructures for PHP
 *
 * @link      https://github.com/SiroDiaz/DataStructures
 * @copyright Copyright (c) 2017 Siro Díaz Palazón
 * @license   https://github.com/SiroDiaz/DataStructures/blob/master/README.md (MIT License)
 */
namespace DataStructures\Lists;

use DataStructures\Lists\Traits\{CountTrait, ArrayAccessTrait};
use DataStructures\Lists\Nodes\SinglyLinkedListNode;
use DataStructures\Exceptions\NotFoundException;
use OutOfBoundsException;
use Iterator;

/**
 * SinglyLinkedList
 *
 * SinglyLinkedList is a singly linked list that has
 * a pointer to the next node but last node points to null.
 *
 * @author Siro Diaz Palazon <siro_diaz@yahoo.com>
 */
class SinglyLinkedList extends ListAbstract {
    use ArrayAccessTrait;
    
    protected $head;
    private $position;
    private $current;

    public function __construct() {
        $this->head = null;
        $this->size = 0;
        $this->position = 0;
        $this->current = &$this->head;
    }

    /**
     * {@inheritDoc}
     */
    protected function search($index) {
        if($index > $this->size - 1 || $index < 0) {
            throw new OutOfBoundsException();
        }

        $current = $this->head;
        $i = 0;
        while($i < $index && $current->next !== null) {
            $current = $current->next;
            $i++;
        }

        return $current;
    }

    /**
     *
     */
    public function searchLast() {
        if($this->head === null) {
            return null;
        }

        $current = $this->head;
        while($current->next !== null) {
            $current = $current->next;
        }

        return $current;
    }

    /**
     * Generator for retrieve all nodes stored.
     * 
     * @return null if the head is null (or list is empty)
     */
    public function getAll() {
        if($this->head === null) {
            return;
        }

        $current = $this->head;
        while($current !== null) {
            yield $current->data;
            $current = $current->next;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function contains($data) : bool {
        if($this->empty()) {
            return false;
        }

        $current = $this->head;
        while($current !== null) {
            if($current->data === $data) {
                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($data) {
        if($this->head === null) {
            return false;
        }
        
        $current = $this->head;
        $i = 0;
        
        while($i < $this->size) {
            if($current->data === $data) {
                return $i;
            }

            $current = $current->next;
            $i++;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function lastIndexOf($data) {
        if($this->head === null) {
            return false;
        }
        
        $current = $this->head;
        $i = 0;
        $pos = false;
        while($i < $this->size) {
            if($current->data == $data) {
                $pos = $i;
            }

            $current = $current->next;
            $i++;
        }

        return $pos;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data) {
        $current = &$this->head;
        $prev = null;
        $i = 0;
        
        if($this->head === null) {
            throw new NotFoundException();
        }

        if($this->head->data === $data) {
            $this->head = &$this->head->next;
            $this->size--;
            return $data;
        }
        
        while($i < $this->size) {
            if($current->data === $data) {
                $prev->next = &$current->next;
                $this->size--;

                return $data;
            }

            $prev = $current;
            $current = $current->next;
        }

        throw new NotFoundException();
    }

    /**
     * Add a new node in the specified index.
     *
     * @param integer $index the position.
     * @param mixed $data the data to be stored.
     */
    protected function insertAt($index, $data) {
        $newNode = new SinglyLinkedListNode($data);
        $current = $this->head;
        $prev = null;
        $i = 0;
        while($i < $index) {
            $prev = $current;
            $current = $current->next;
            $i++;
        }
        
        $prev->next = &$newNode;
        $newNode->next = &$current;
    }

    protected function insertEnd($data) {
        $newNode = new SinglyLinkedListNode($data);
        if($this->head === null) {
            $this->head = &$newNode;
            $this->current = &$this->head;
        } else {
            $current = $this->head;
            while($current->next !== null) {
                $current = $current->next;
            }
            $current->next = &$newNode;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function insertBeginning($data) {
        $newNode = new SinglyLinkedListNode($data);
        if($this->head === null) {
            $this->head = &$newNode;
        } else {
            $newNode->next = $this->head;
            $this->head = &$newNode;
        }
    }

    /**
     * Delete a node in the given position and returns it back.
     *
     * @param integer $index the position.
     * @throws OutOfBoundsException if index is negative
     *  or is greater than the size of the list.
     */
    public function delete($index) {
        if($index < 0) {
            throw new OutOfBoundsException();
        }
        if($this->head === null) {
            return null;
        }

        if($index >= $this->size) {
            return null;    // It should return an exception
        }

        if($index === 0) {
            $node = $this->head;
            $this->head = $this->head->next;
            $this->current = &$this->head;
            $this->size--;
            return $node->data;
        }
        
        $i = 0;
        $current = $this->head;
        $prev = $current;
        while($i < $index && $current->next !== null) {
            $prev = $current;
            $current = $current->next;
        }

        if($index === $this->size - 1) {
            $prev->next = null;
            $this->size--;
            return $current->data;
        } else {
            $prev->next = $current->next;
        }
        $this->size--;

        return $prev->data;
    }

    protected function deleteBeginning() {}

    protected function deleteAt($index) {}

    protected function deleteEnd() {}

    

    /**
     * Reset the cursor position.
     */
    public function rewind() {
        $this->position = 0;
        $this->current = $this->head;
    }

    /**
     * Returns the current node data.
     *
     * @return mixed
     */
    public function current() {
        return $this->current->data;
    }

    /**
     * Key or index that indicates the cursor position.
     *
     * @return integer The current position.
     */
    public function key() {
        return $this->position;
    }

    /**
     * Move the cursor to the next node and increments the
     * position counter.
     */
    public function next() {
        ++$this->position;
        $this->current = $this->current->next;
    }

    /**
     * Returns if the current pointer position is valid.
     *
     * @return boolean true if pointer is not last, else false.
     */
    public function valid() {
        return $this->current !== null;
    }
}