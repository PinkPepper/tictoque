<?php

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;

/**
 * Blog controller.
 *
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * Lists all Article entities.
     *
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('frontoffice/blog/article/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/new", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuteur($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_show', array('id' => $article->getId(), 'titre' => $article->getSlug()));
        }

        return $this->render('frontoffice/blog/article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}/{titre}", name="article_show")
     * @Method({"GET","POST"})
     */
    public function showAction(Article $article, Request $request)
    {

        $deleteForm = $this->createDeleteForm($article);

        $commentaire = new Commentaire();
        $formCom = $this->createForm('AppBundle\Form\CommentaireType', $commentaire);
        $formCom->handleRequest($request);

        if ($formCom->isSubmitted() && $formCom->isValid()) {
            $commentaire->setAuteur($this->getUser());
            $commentaire->setArticle($article);

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('article_show', array('id' => $article->getId(), 'titre' => $article->getSlug()));
        }

        return $this->render('frontoffice/blog/article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
            'form'=>$formCom->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/article/edit", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
        }

        return $this->render('frontoffice/blog/article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('blog_index');
    }

    /**
     * Creates a form to delete a Article entity.
     *
     * @param Article $article The Article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
