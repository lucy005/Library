<?php

namespace Library\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Library\MainBundle\Form\Type\SearchFormType;
use Library\MainBundle\Model\OnePage;

class FindBooksController extends Controller
{
    public function findAction(Request $request) {
        $this->get('session')->getFlashBag()->clear();
        $form = $this->createForm(new SearchFormType());
        $form->handleRequest($request);
        if($form->isValid()) {
            $found = $this->searcher($form->getData());
            if(count($found) > 0) {
                $this->get('session')->getFlashBag()->add('search_data', $found);
                return $this->redirect($this->generateUrl('show_search_result', array('page' => 1)));
            } else {
                return $this->render('LibraryMainBundle:FindBooks:notFound.html.twig');
            }
        }
        return $this->render('LibraryMainBundle:FindBooks:search.html.twig', array('form' => $form->createView()));
    }
    
    public function showSearchResultAction($page) {
        $found = $this->get('session')->getFlashBag()->get('search_data');
        if(count($found) > 0) {
            $count = count($found[0]);
            $maxPage = ceil( $count / OnePage::MAX_SIZE );
            $onePage = new OnePage($page, $found[0], $maxPage);
            
            $this->get('session')->getFlashBag()->add('search_data', $found[0]);
            return $this->render('LibraryMainBundle:FindBooks:showSearchResult.html.twig',
                    array('page' => $onePage));
        } else {
            return $this->redirect($this->generateUrl('search_recipt'));
        }
    }
    
    
    
    /**
     * Searches recipts by criterie
     * 
     * @param Form $data
     * @return array
     */
    private function searcher($data) {
        $arr = [];
        $em = $this->getDoctrine()->getManager()->getRepository('LibraryMainBundle:Book');
        $i = 0;
        if($data['title'] != null) {
            $hits = $em->findBy(array('name' => $data['title']));
            foreach($hits as $hit) {
                $arr[$i++] = $hit;
            }
        } elseif($data['author'] != null) {
            $hits = $em->findBy(array('author' => $data['author']));
            foreach($hits as $hit) {
                $arr[$i++] = $hit;
            }
        } elseif($data['publishing'] != null) {
            $hits = $em->findBy(array('publishing' => $data['publishing']));
            foreach($hits as $hit) {
                $arr[$i++] = $hit;
            }
        } elseif($data['category'] != null) {
            $em = $this->getDoctrine()->getManager()->getRepository('LibraryMainBundle:Category');
            $hits = $em->findOneBy(array('name' => $data['category']->getName()))->getBooks();
            foreach($hits as $hit) {
                $arr[$i++] = $hit;
            }
        } else {
            return $this->redirect($this->generateUrl('find_books'));
        }
        return $this->removeDuplicates($arr);
    }
    
    private function removeDuplicates($arr) {
        $count = count($arr);
        for($i = 0; $i < $count; $i++) {
            for($j = $i + 1; $j < $count; $j++) {
                if($arr[$i]->getId() == $arr[$j]->getId()) {
                    unset($arr[$j]);
                }
            }
        }
        $temp = [];
        $i = 0;
        foreach($arr as $elem) {
            $temp[$i++] = $elem;
        }
        return $temp;
    }
}