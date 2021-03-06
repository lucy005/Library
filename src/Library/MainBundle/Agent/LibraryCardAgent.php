<?php

namespace Library\MainBundle\Agent;

use Library\MainBundle\Model\BaseAgent;

/**
 * Borrowing, returning and showing actually borrowed books
 * @author Piotr
 */
class LibraryCardAgent extends BaseAgent {
    
    /**
     * Gets users' library card
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return array
     */
    public function showBorrowed(\Application\Sonata\UserBundle\Entity\User $user) {
        return $user->getLibraryCard();
    }
    
    /**
     * 
     * @param integer $id
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return boolean
     */
    public function borrowBook($id, $user) {
        if($user != null) {
            $book = $this->em->getRepository('LibraryMainBundle:Book')->find($id);
            $borrowed = new \Library\MainBundle\Entity\LibraryCard();
            $borrowed->setBook($book);
            $borrowed->setUser($user);
            
            $this->em->persist($borrowed);
            $this->em->flush();
            
            return true;
        }
        return false;
    }
    
    /**
     * Returns selected book
     * @param integer $cardId
     */
    public function returnBook($cardId) {
        $card = $this->em->find('LibraryMainBundle:LibraryCard', $cardId);
        $card->bookReturned();
        $this->em->flush();
    }
}
